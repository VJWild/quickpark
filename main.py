from fastapi import FastAPI, File, UploadFile, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from fast_alpr import ALPR
from pathlib import Path
import tempfile
import shutil
import uvicorn
import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI(
    title="ALPR - Escáner de Placas",
    description="API para detección y reconocimiento automático de placas vehiculares.",
    version="1.0.0",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

# Inicializar el modelo una sola vez al arrancar el servidor
logger.info("Cargando modelos ALPR...")
alpr = ALPR(
    detector_model="yolo-v9-t-384-license-plate-end2end",
    ocr_model="cct-xs-v2-global-model",
)
logger.info("Modelos cargados correctamente.")


@app.get("/", tags=["Health"])
def root():
    return {"status": "ok", "message": "Servidor ALPR activo."}


@app.get("/health", tags=["Health"])
def health():
    return {"status": "healthy"}


@app.post("/scan", tags=["ALPR"])
async def scan_plate(file: UploadFile = File(...)):
    """
    Recibe una imagen (jpg, jpeg, png, webp) y devuelve las placas detectadas.
    """
    allowed_types = {"image/jpeg", "image/jpg", "image/png", "image/webp"}
    if file.content_type not in allowed_types:
        raise HTTPException(
            status_code=415,
            detail=f"Tipo de archivo no soportado: '{file.content_type}'. Use jpg, png o webp.",
        )

    # Guardar imagen temporalmente
    suffix = Path(file.filename).suffix or ".jpg"
    with tempfile.NamedTemporaryFile(delete=False, suffix=suffix) as tmp:
        shutil.copyfileobj(file.file, tmp)
        tmp_path = tmp.name

    try:
        logger.info(f"Procesando imagen: {file.filename}")
        results = alpr.predict(tmp_path)
    except Exception as e:
        logger.error(f"Error al procesar imagen: {e}")
        raise HTTPException(status_code=500, detail=f"Error al procesar la imagen: {str(e)}")
    finally:
        Path(tmp_path).unlink(missing_ok=True)

    if not results:
        return JSONResponse(
            status_code=200,
            content={"filename": file.filename, "plates_found": 0, "plates": []},
        )

    plates = []
    for result in results:
        plate_data = {}

        # Detección
        if result.detection:
            plate_data["bounding_box"] = {
                "x1": result.detection.bounding_box.x1,
                "y1": result.detection.bounding_box.y1,
                "x2": result.detection.bounding_box.x2,
                "y2": result.detection.bounding_box.y2,
            }
            plate_data["detection_confidence"] = round(result.detection.confidence, 4)

        # OCR
        if result.ocr:
            # ocr.text puede ser string o lista de candidatos
            text = result.ocr.text
            plate_data["plate_text"] = text[0] if isinstance(text, list) else text

            # ocr.confidence puede ser float o lista de floats
            conf = result.ocr.confidence
            if isinstance(conf, list):
                plate_data["ocr_confidence"] = round(conf[0], 4) if conf else None
            else:
                plate_data["ocr_confidence"] = round(conf, 4)
        else:
            plate_data["plate_text"] = None
            plate_data["ocr_confidence"] = None

        plates.append(plate_data)

    return JSONResponse(
        status_code=200,
        content={
            "filename": file.filename,
            "plates_found": len(plates),
            "plates": plates,
        },
    )


if __name__ == "__main__":
    uvicorn.run("main:app", host="0.0.0.0", port=8000, reload=False)