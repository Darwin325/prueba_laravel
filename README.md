# Instrucciones:
- Instalar los paquetes de laraven con `composer install`
- Ejecutar el servidor de pureba con `php artisan serve`
- Crear un link simbólico para el manejo de archivos con `php artisan storage:link`
y en el archivo **.env** cambiar la variable `FILESYSTEM_DISK=public`  
Cambiar a localhost la variable `APP_URL=http://localhost:8000`

Para ejecutar consulta a una base de datos configurar el archivo
.env con una base de datos respectiva creada.

##### Ejecutar los comandos para el manejo de la base de datos
- Crear las tablas: `php artisan migrate`
- Ejecutar los seeder para tener datos de prueba: `php artisan db:seed`

***
### Rutas de la api:
##### 1. Rutas para productos
- Consultar la lista de productos método **GET**:
`/api/productos`
- Consultar un producto método **GET**:
`/api/productos/{producto_id}`
- Guardar un producto método **POST**:
`/api/productos`  
**Cuerpo de la petición:**
    - `nombre => reqerido, texto`
    - `descripcion => requerido, texto`,
    - `foto => requerido, imagen`
    - `cantidad => requerido, número`
    - `precio => requerido, número`
    - `iva => requerido, número`
    - `vendedor_id => requerido, usuario de tipo vendedor`  
- Actualizar un producto, método **PUT|PATCH:**
`/api/productos/{producto_id}`  
**Cuerpo de la petición**: Cualquier de los atributos mencionados en la ruta anterior,
con la exepción que no son requeridos.
- Borrar un producto, método **DELETE:**
`/api/productos/{producto_id}`

*******
##### 1. Rutas para ventas
- Consultar la lista de ventas método **GET**:
`/api/ventas`
- Consultar una venta método **GET**:
`/api/ventas/{ventas_id}`
- Guardar una venta método **POST**:
`/api/productos`  
**Cuerpo de la petición:**
    - `cliente_id => reqerido, usuario de tipo cliente`
    - `productos => requerido, array de objetos con las llaves: producto_id, cantidad
    ejemplo, [{producto_id: 3, cantidad: 3}]`
- Borrar una venta, método **DELETE:**
`/api/ventas/{venta_id}`
