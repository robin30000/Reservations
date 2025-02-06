# Reservation System API

## Descripción
Este proyecto proporciona una API para gestionar reservas de usuarios en sitios. Permite crear, consultar y administrar las reservas, garantizando que los usuarios no realicen reservas duplicadas ni con fechas pasadas.

## Técnicas Utilizadas
- **Lenguaje**: PHP 8.x
- **Framework**: Laravel 11
- **Base de datos**: MySQL
- **Autenticación**: No implementada directamente (se asume que el usuario está autenticado)
- **Validación**: Uso del sistema de validaciones de Laravel para garantizar que los datos sean correctos antes de ser procesados.
- **Optimización**: Uso de índices en las consultas SQL y claves foráneas para mantener la integridad de los datos.

## Arquitectura
La arquitectura del proyecto sigue el patrón **MVC (Modelo-Vista-Controlador)**:
1. **Modelo (Model)**: Representa las entidades de negocio (Usuarios, Sitios, Reservas) y se encarga de interactuar con la base de datos.
2. **Vista (View)**: No está implementada en este proyecto, ya que es una API RESTful que devuelve datos en formato JSON.
3. **Controlador (Controller)**: Maneja las solicitudes HTTP, valida los datos y llama a los servicios correspondientes para crear las reservas.

**Estructura del Proyecto:**


**Decisiones Técnicas Tomadas:**
- La API utiliza **Laravel** por su robustez y facilidad para manejar solicitudes HTTP.
- Las **relaciones Eloquent** son utilizadas para gestionar las relaciones entre usuarios, sitios y reservas.
- Las reservas se gestionan con una tabla **reservations**, y se aplican **restricciones de unicidad** (una reserva por usuario en una fecha específica) para evitar duplicados.
- Se optó por **validar las fechas de inicio y fin** antes de procesar la solicitud para evitar errores lógicos y mejorar la calidad de los datos.

## Elementos Clave
- **Validaciones**: Se validan los parámetros de entrada para asegurarse de que el `user_id` y `site_id` existen, que las fechas sean futuras y que la fecha de fin sea posterior a la fecha de inicio.
- **Seguridad**: Aunque no se implementa autenticación en esta versión, se recomienda agregarla para proteger las rutas de la API.
- **Rendimiento**: Se optimizan las consultas SQL mediante **índices** en las tablas de reservas y claves foráneas para garantizar la integridad de los datos y mejorar la velocidad de las consultas.
- **Optimización**: Se utiliza la función `load` de Eloquent para evitar consultas innecesarias al obtener los datos relacionados con las reservas (usuario y sitio).

## Complicaciones Encontradas
Durante el desarrollo, se presentaron algunas complicaciones:
1. **Gestión de reservas superpuestas**: Fue necesario implementar una validación para asegurarse de que no existan reservas que se solapen en el mismo sitio y para el mismo usuario. Esto requirió la creación de consultas SQL personalizadas.
2. **Fechas futuras**: Implementar la validación para que las reservas solo puedan hacerse para fechas futuras fue más complicado de lo esperado debido a la conversión y comparación de formatos de fecha.

**Soluciones**:
- Se implementó una función que comprueba las reservas superpuestas utilizando un rango de fechas.
- Se utilizó la validación de Laravel para asegurarse de que las fechas de inicio y fin sean correctas.

## Posibles Mejoras
1. **Autenticación**: Se podría agregar autenticación basada en JWT para asegurar que los usuarios estén autenticados antes de hacer una reserva.
2. **Gestión de cancelaciones**: Implementar una funcionalidad para cancelar reservas y evitar que se acumulen reservas innecesarias.
3. **Paginación en las respuestas**: Si las reservas se vuelven muy grandes, implementar paginación en las respuestas para mejorar el rendimiento y la usabilidad.
4. **Historial de reservas**: Añadir una función para que los usuarios puedan ver un historial completo de sus reservas.

## Instalación y Ejecución
1. Clona el repositorio:
   ```bash
   git clone https://github.com/usuario/reservation-api.git
2. Instala las dependencias:
   ```bash
   composer install
   
3. Configura la base de datos en el archivo .env.
4. Ejecuta las migraciones:
   ```bash
   php artisan migrate
   
5. Ejecutar Seeders
   Para tener algunos datos de prueba en la base de datos, ejecuta los siguientes comandos:
   ```bash
    php artisan db:seed --class=UserSeeder
    php artisan db:seed --class:SiteSeeder

6. Inicia el servidor:
   ```bash
   php artisan serve
   
7. Accede a la API en http://localhost:8000.

# Consultas SQL

A continuación, se presentan algunas consultas SQL clave para obtener información relevante de la base de datos en el sistema de reservas.

1. Sitios que no han sido reservados
Esta consulta devuelve todos los sitios que no tienen ninguna reserva asociada.

```sql
SELECT s.id, s.name 
FROM sites s
LEFT JOIN reservations r ON s.id = r.site_id
WHERE r.id IS NULL;

```

2. Listado de reservas por usuario y sitio
Esta consulta devuelve todos los sitios que no tienen ninguna reserva asociada.

```sql
SELECT
    r.id AS reservation_id,
    u.id AS user_id,
    u.name AS user_name,
    s.id AS site_id,
    s.name AS site_name,
    r.start_date,
    r.end_date
FROM reservations r
         JOIN users u ON r.user_id = u.id
         JOIN sites s ON r.site_id = s.id
ORDER BY r.start_date DESC;


```
# url documentacion Swagger:
http://127.0.0.1:8000/api/documentation
