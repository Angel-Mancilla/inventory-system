#!/bin/sh
set -e

pnpm run dev &
VITE_PID=$!

php artisan serve --host=0.0.0.0 --port=8000 &
SERVE_PID=$!

# Si cualquiera de los dos procesos muere, tira todo el contenedor
wait -n $VITE_PID $SERVE_PID
exit $?

# #!/bin/sh
# set -e

# # Levanta el servidor de Vite (HMR) en segundo plano
# pnpm run dev &

# # pnpm run dev -- --host 0.0.0.0 &

# # Levanta Laravel en primer plano (mantiene vivo el contenedor)
# php artisan serve --host=0.0.0.0 --port=8000