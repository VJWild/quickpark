# **Documentación Oficial QuickPark**

**Desarrollador:** Victor Jesus González González  
**Estudio:** EVG Dev Studio

## **1\. Comandos de Inicialización del Sistema**

Para levantar el entorno de desarrollo completo, incluyendo el microservicio de reconocimiento óptico (OCR) basado en FastAPI/Uvicorn, ejecuta los siguientes comandos en diferentes pestañas de tu terminal (Laragon/Local):

`# 1. Iniciar servidor Backend/Frontend (PHP Laravel)`  
`php artisan serve`

`# 2. Compilar assets en tiempo real (Tailwind CSS / Alpine.js)`  
`npm run dev`

`# 3. Iniciar el Motor de Escáner de Matrículas (Python OCR API)`  
`# (Asegúrate de activar tu entorno virtual si usas uno: venv\Scripts\activate)`  
`python -m uvicorn main:app --host 127.0.0.1 --port 8000`

`# 4. Limpiar cachés (Solo en caso de errores 403 o rutas no encontradas)`  
`php artisan route:clear`  
`php artisan cache:clear`  
`php artisan config:clear`

## **2\. Identidad Visual y Paleta de Colores (SaaS Premium)**

El sistema utiliza una arquitectura visual moderna basada en **Glassmorphism (acristalamiento)**, bordes orgánicos suaves y sombras expansivas, diseñado para reducir la carga cognitiva del operador en jornadas largas.

| Elemento Visual | Clases / Implementación Tailwind | Uso Principal en el Sistema |
| :---- | :---- | :---- |
| Gradiente Corporativo | `from-qp-blue to-qp-indigo` | Botones CTA principales (Guardar, Actualizar) e Iconos de cabecera de sección. |
| Fondos Acristalados | `bg-white/60 backdrop-blur-2xl` | Contenedores maestros, Modales de creación/edición, y envoltorios de Tablas. |
| Efecto "Lift" Interactivo | `hover:-translate-y-1 hover:scale-[1.02]` | Se utiliza en las filas de las tablas de datos y tarjetas de estadísticas para simular elevación física. |
| Notificaciones (Toasts) | `fixed top-28 left-1/2 z-[100]` | Mensajes dinámicos de Éxito (Verde) y Error (Rojo) que flotan bajo la barra de navegación. |

## **3\. Estructura de Vistas Refactorizadas**

* **Directorio de Clientes:** Tabla separada (border-separate) con efecto de levantamiento individual. Buscador dinámico integrado con Alpine.js y Modal VIP de edición de conductores.  
* **Configuración de Tarifas:** Tarjetas interactivas que emplean reactividad para ocultar/mostrar campos de cobro dependiendo del modo (Plano, Hora, Mixto).  
* **Historial de Registros:** Panel gerencial con métricas rápidas (tickets facturados, ingresos totales) y vista detallada con indicadores luminosos de estado de entrada/salida.  
* **Auditoría de Turnos:** Sistema avanzado de visualización de arqueos. Incluye lógica de colores para descuadres de caja (verde si cuadra exacto, rojo si falta dinero, ámbar si sobra).  
* **Gestión de Puestos (Capacidad):** Sistema de creación masiva algorítmica y eliminación múltiple (bulk delete) asistida por checkboxes, con protección estricta sobre puestos en estado "OCUPADO".  
* **Información de la Empresa & Control de Divisas:** Interfaz centrada estilo "display financiero", con tipografía ampliada para maximizar la legibilidad de la moneda y los datos fiscales impresos en los tickets.

## **4\. Arquitectura de Controladores y Lógica Backend**

* **TicketController:** Motor matemático encargado del cálculo de tarifas en tiempo real según la modalidad de cobro parametrizada, y puente para el escáner OCR.  
* **ParkingSpaceController:** Integración de bucles iterativos y el método firstOrCreate de Eloquent para la generación secuencial masiva, mitigando choques por duplicidad de identificadores.  
* **Middleware (CheckRole):** Vigilante central que gestiona la redirección segura de los perfiles de usuario, bloqueando módulos no autorizados de manera silenciosa (redirigiendo) o estricta (Error 403).

## **5\. Roadmap Próxima Fase: Integración de Hardware**

El próximo paso para el sistema implica la salida del entorno puramente de software hacia el control de electrónica externa:

1. Desarrollo de controladores con capacidad de petición HTTP/Sockets para comunicarse con microcontroladores (Ej. ESP32).  
2. Automatización de pulso de apertura para balancines de entrada vinculado a la emisión y guardado del ticket inicial.  
3. Automatización de pulso de apertura para balancines de salida estrictamente vinculado a la validación de la factura (Checkout).
