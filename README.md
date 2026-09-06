# Inventory System

Sistema de inventario/ventas para tienda de cómputo. Laravel 13 + Inertia + Vue 3, corriendo en Docker.

## Stack

- **Backend:** PHP 8.4, Laravel 13, Inertia 3
- **Frontend:** Vue 3 (Composition API), TypeScript, Tailwind, shadcn-vue
- **Auth:** Laravel Fortify (email/password) + Spatie Permission (roles) + Socialite (OAuth) — Fortify integrado desde el starter kit; Spatie/Socialite se agregan aparte
- **Base de datos:** MySQL 8.0
- **Gestor de paquetes JS:** pnpm
- **Reportes/PDF:** Browsershot (Chromium headless, ya incluido en la imagen)
- **Variables de entorno:** gestionadas con [Doppler](https://doppler.com)

---

## Requisitos previos en la PC nueva

- Docker y Docker Compose instalados.
- (Opcional pero recomendado) Doppler CLI, si vas a seguir sincronizando el `.env` raíz desde ahí.
- Nada de PHP, Composer, Node o pnpm instalados localmente — todo corre dentro de los contenedores.

---

## Instalación en una PC nueva (proyecto ya existente en GitHub)

### 1. Clonar el repositorio

```bash
git clone <url-de-tu-repo> inventory-system
cd inventory-system
```

### 2. Crear los archivos `.env` (NO se suben a git, hay que recrearlos)

Hay **dos** `.env` distintos en este proyecto, cada uno con un propósito diferente:

| Archivo | Para qué sirve | Lo usa |
|---|---|---|
| `.env` (raíz) | Variables que Docker Compose inyecta al construir/levantar contenedores (credenciales de MySQL, UID/GID) | `docker-compose.yml` |
| `src/.env` | Configuración de la aplicación Laravel (conexión a BD, `APP_KEY`, etc.) | Laravel dentro del contenedor `app` |

Copia las plantillas y rellena los valores reales (si usas Doppler, ejecuta `doppler run` o descarga el secreto directo, según cómo lo tengas configurado):

```bash
cp .env.example .env
cp src/.env.example src/.env
```

**Importante:** las credenciales de MySQL (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) deben ser **idénticas** en ambos archivos — el `.env` raíz es el que crea el usuario/base en el contenedor de MySQL, y `src/.env` es con el que Laravel se conecta a esa misma base.

Si tu usuario de Linux no tiene UID/GID 1000 (verifica con `id -u` y `id -g`), exporta las variables antes de construir:
```bash
export UID=$(id -u)
export GID=$(id -g)
```

### 3. Construir las imágenes

```bash
docker compose build
```

### 4. Instalar dependencias (Composer + pnpm)

El código PHP/JS ya viene en el repo (se clonó con `git clone`), pero `vendor/` y `node_modules/` están en `.gitignore` — hay que reinstalarlos:

```bash
docker compose run --rm --no-deps app composer install
docker compose run --rm --no-deps app pnpm install
```

Si pnpm te avisa de `ERR_PNPM_IGNORED_BUILDS`, aprueba los scripts de instalación necesarios (típicamente solo `vue-demi`):
```bash
docker compose run --rm --no-deps app pnpm approve-builds
```

### 5. Generar la `APP_KEY` (si `src/.env` no trae ya una)

```bash
docker compose run --rm --no-deps app php artisan key:generate
```

### 6. Levantar todo el stack

```bash
docker compose up -d --build
```

### 7. Esperar a que MySQL inicialice y correr migraciones + seeders

La primera vez que se crea el volumen de MySQL tarda unos segundos en estar lista para aceptar conexiones.

```bash
docker compose exec app php artisan migrate --seed
```

### 8. Verificar

- **App:** http://localhost:8000
- **Adminer** (cliente visual de MySQL): http://localhost:8080 — servidor `db`, usuario/contraseña los de tu `.env`
- **Logs en vivo:**
  ```bash
  docker compose logs -f app
  ```

---

## Comandos del día a día

```bash
# Levantar el stack
docker compose up -d

# Bajarlo
docker compose down

# Ejecutar cualquier comando artisan dentro del contenedor ya corriendo
docker compose exec app php artisan <comando>

# Ejecutar composer/pnpm dentro del contenedor ya corriendo
docker compose exec app composer <comando>
docker compose exec app pnpm <comando>

# Entrar a una shell dentro del contenedor
docker compose exec app bash

# Ver logs
docker compose logs -f app
```

> Usa `docker compose run --rm --no-deps app <comando>` (contenedor "de un solo uso") solo cuando el stack todavía **no** está levantado. Si `app` ya está corriendo, usa `exec` — es más rápido y comparte el mismo proceso.

---

## Estructura del proyecto

```
inventory-system/
├── docker/
│   └── entrypoint.sh          # Levanta Vite (HMR) + artisan serve juntos
├── src/                        # Proyecto Laravel completo (código de la app)
│   ├── .env                    # NO se sube a git — ver paso 2
│   ├── .env.example             # Sí se sube — plantilla sin secretos
│   ├── app/
│   ├── resources/js/            # Componentes Vue, páginas Inertia
│   └── ...
├── .dockerignore
├── .env                         # NO se sube a git — ver paso 2
├── .env.example                  # Sí se sube — plantilla sin secretos
├── docker-compose.yml
├── Dockerfile
└── README.md
```

---

## `.gitignore` recomendado (raíz del proyecto)

Asegúrate de que tu `.gitignore` en la raíz incluya al menos:

```gitignore
.env
src/.env
src/node_modules
src/vendor
src/.pnpm-store
src/database/database.sqlite
src/storage/*.key
src/public/hot
src/public/build
```

(Laravel ya trae su propio `.gitignore` dentro de `src/` con la mayoría de estas rutas — solo confirma que `.env` y `node_modules` estén cubiertos.)

---

## Notas y troubleshooting

**"Connection refused" al correr `migrate`:** MySQL tardó en inicializar. Espera unos segundos y reintenta.

**Error `EACCES` en `/root/.npm` o `npm error code EACCES`:** el usuario dentro del contenedor no tiene un `$HOME` válido. Confirma que la imagen tenga el usuario `appuser` creado (ver `Dockerfile`) y que `docker compose run --rm --no-deps app env | grep -i home` devuelva `HOME=/home/appuser`.

**Vite carga pero la consola del navegador muestra errores de WebSocket/HMR:** revisa que `vite.config.js` tenga `server.hmr.host` configurado a `localhost` (o al host donde abres el navegador), no a la IP interna del contenedor.

**`ERR_PNPM_IGNORED_BUILDS` al correr `pnpm install`:** normal desde pnpm 9+, es una medida de seguridad. Corre `pnpm approve-builds` y aprueba los paquetes necesarios (usualmente solo `vue-demi`).

**Necesitas resetear la base de datos completa (solo en desarrollo, nunca con datos reales):**
```bash
docker compose exec app php artisan migrate:fresh --seed
```
