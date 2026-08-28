# Generar PDF por pagina

Genera un PDF de **una** pagina del sitio Rafiki (el que corre en `http://localhost:8080`)
usando el Chrome ya instalado en el equipo. El PDF se guarda con el nombre de la pagina.

## Requisitos

- El entorno WordPress levantado: `docker compose up -d` desde `wordpress/`.
- Node.js (ya instalado).
- Google Chrome o Edge instalado (se detecta solo).

## Instalacion (una sola vez)

```bash
cd tools/pdf
npm install
```

Esto solo baja `puppeteer-core` (unos pocos KB); **no** descarga otro Chromium.

## Uso

Desde `tools/pdf/`:

```bash
# Home  ->  genera  home.pdf  en la raiz del repo
node generar-pdf.js home

# Cualquier pagina por su slug  ->  <slug>.pdf
node generar-pdf.js why-rafiki
node generar-pdf.js meet-rafiki
node generar-pdf.js plan-your-trip

# Un slug que no este en la lista tambien sirve
node generar-pdf.js bring-your-group

# URL completa
node generar-pdf.js http://localhost:8080/rafiki-journal/
```

### Opciones

| Opcion             | Efecto                                                              |
|--------------------|--------------------------------------------------------------------|
| `--out <carpeta>`  | Carpeta de salida (por defecto: raiz del repo)                     |
| `--name <archivo>` | Fuerza el nombre del PDF (sin `.pdf`)                              |
| `--base <url>`     | Base del sitio (por defecto `http://localhost:8080`)              |
| `--single`         | Un PDF de una sola pagina larga (tipo captura) en vez de A4        |
| `--width <px>`     | Ancho de escritorio del render (por defecto `1440`)               |
| `--list`           | Lista las paginas conocidas                                        |

Ejemplos:

```bash
node generar-pdf.js home --single
node generar-pdf.js why-rafiki --out ../../entregables --name why-rafiki-v2
```

## Paginas conocidas

`home`, `why-rafiki`, `meet-rafiki`, `plan-your-trip`, `bring-your-group`,
`rafiki-journal`, `home-draft-6-sections`, `shop`, `cart`, `checkout`, `my-account`.

(Se aceptan otros slugs y URLs completas igualmente.)
