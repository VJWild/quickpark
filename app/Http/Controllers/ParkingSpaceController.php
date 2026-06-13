<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpace;
use Illuminate\Http\Request;

class ParkingSpaceController extends Controller
{
    /**
     * VISTA PÚBLICA (Solo lectura para los clientes)
     */
    public function publicIndex()
    {
        $spaces = ParkingSpace::all();
        $total = $spaces->count();
        $available = $spaces->where('status', 'DISPONIBLE')->count();
        $occupied = $spaces->where('status', 'OCUPADO')->count();

        return view('mapa', compact('spaces', 'total', 'available', 'occupied'));
    }

    /**
     * VISTA ADMINISTRATIVA (Listado y Gestión)
     */
    public function index()
    {
        // Traemos los puestos ordenados por su ID o número
        $spaces = ParkingSpace::orderBy('id', 'asc')->get();
        $totalSpaces = $spaces->count();

        return view('admin.parking_spaces.index', compact('spaces', 'totalSpaces'));
    }

    /**
     * CREACIÓN (MASIVA O INDIVIDUAL CON SECUENCIA)
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_number' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1|max:100',
        ]);

        $prefix = strtoupper(trim($request->prefix ?? ''));
        $start = (int) $request->start_number;
        $qty = (int) $request->quantity;
        $createdCount = 0;

        // Bucle para crear los puestos siguiendo la secuencia matemática
        for ($i = 0; $i < $qty; $i++) {
            $num = $start + $i;
            $spaceName = $prefix . $num;

            // firstOrCreate evita que se guarden duplicados si el puesto (ej: "A1") ya existe
            // de esta forma, el sistema sigue creando los demás sin detenerse por un error.
            $space = ParkingSpace::firstOrCreate(
                ['space_number' => $spaceName],
                ['status' => 'DISPONIBLE']
            );

            // Contamos solo los que realmente se crearon (ignorando los duplicados)
            if($space->wasRecentlyCreated) {
                $createdCount++;
            }
        }

        return back()->with('success', "$createdCount puestos generados exitosamente en el mapa.");
    }

    /**
     * ELIMINAR UN PUESTO (INDIVIDUAL)
     */
    public function destroy(ParkingSpace $space)
    {
        // Medida de seguridad vital:
        if ($space->status === 'OCUPADO') {
            return back()->withErrors(['error' => 'No puedes eliminar un puesto que tiene un vehículo estacionado actualmente.']);
        }

        $space->delete();
        return back()->with('success', 'Puesto eliminado del mapa.');
    }

    /**
     * ELIMINACIÓN MASIVA (NUEVO MÉTODO)
     */
    public function bulkDestroy(Request $request)
    {
        $idsString = $request->input('ids');

        if (!$idsString) {
            return back()->withErrors(['error' => 'No se seleccionaron puestos para eliminar.']);
        }

        // Convertimos el string de la vista "1,2,3" en un arreglo [1, 2, 3]
        $idsArray = explode(',', $idsString);

        // Filtramos solo los puestos que están disponibles (Protección absoluta contra borrado de ocupados)
        $spacesToDelete = ParkingSpace::whereIn('id', $idsArray)->where('status', 'DISPONIBLE')->get();
        $count = $spacesToDelete->count();

        if ($count === 0) {
            return back()->withErrors(['error' => 'No se eliminó ningún puesto. Los puestos seleccionados están ocupados.']);
        }

        // Procedemos a eliminar los permitidos
        ParkingSpace::whereIn('id', $spacesToDelete->pluck('id'))->delete();

        // Si el usuario intentó borrar puestos mezclados (unos libres y otros ocupados)
        if ($count < count($idsArray)) {
            return back()->withErrors(['error' => "Se eliminaron $count puestos. Los puestos OCUPADOS fueron protegidos e ignorados."]);
        }

        return back()->with('success', "Se eliminaron $count puestos correctamente.");
    }
}
