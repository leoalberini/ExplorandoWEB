# MANUAL DE USUARIO
## Proyecto ExplorandoWEB

**Actividad:** ADA 6. Proyecto Web con CRUD, base de datos y autenticación  
**Tecnologías:** PHP, MySQL/MariaDB, HTML y CSS.

### 1. Introducción

ExplorandoWEB es un sitio web educativo que permite consultar información relacionada con el desarrollo y la evolución de las tecnologías web. También cuenta con una tienda de recursos tecnológicos que permite administrar productos mediante un sistema de acceso para el administrador.

### 2. Acceso al sitio web

Para utilizar el sistema localmente, se deben realizar los siguientes pasos:

1. Abrir el panel de control de XAMPP.
2. Iniciar los servicios Apache y MySQL.
3. Abrir un navegador web.
4. Ingresar a la dirección `http://localhost/ExplorandoWEB/`.

### 3. Navegación del sitio

El sitio cuenta con las siguientes secciones:

- **Inicio:** página principal.
- **Historia:** información sobre la historia de la Web.
- **Galería:** contenido visual.
- **Tecnologías:** información sobre tecnologías web.
- **Línea del tiempo:** acontecimientos importantes de la evolución de la Web.
- **Contacto:** formulario para enviar mensajes.
- **Tienda:** catálogo de recursos tecnológicos.
- **Administración:** acceso al inicio de sesión del administrador.

### 4. Uso de la tienda

Para consultar los productos:

1. Seleccionar la sección Tienda.
2. Revisar los productos disponibles.
3. Consultar su descripción y precio.
4. Presionar el botón Agregar al carrito para seleccionar un producto.

### 5. Uso del carrito de compras

El carrito permite realizar las siguientes acciones:

- Consultar los productos agregados.
- Modificar las cantidades.
- Eliminar productos individuales.
- Vaciar el carrito completo.
- Consultar los subtotales y el total de los productos seleccionados.

Para acceder al carrito, se debe seleccionar la opción Ver mi carrito de compras desde la tienda.

### 6. Inicio de sesión

Para acceder al apartado administrativo:

1. Seleccionar Administración en el menú.
2. Ingresar el correo electrónico registrado del administrador.
3. Introducir la contraseña correspondiente.
4. Presionar Ingresar.

El sistema verifica las credenciales y permite el acceso al panel administrativo cuando son correctas.

### 7. Administración de productos

Una vez iniciada la sesión, el administrador puede consultar los productos registrados y realizar las siguientes operaciones:

**Registrar productos:** seleccionar Registrar nuevo producto, completar el código, nombre, descripción y precio, y presionar Registrar producto.

**Consultar productos:** revisar la tabla de productos registrados, incluyendo su identificador, código, nombre, precio y estado.

**Modificar productos:** seleccionar Editar en el producto correspondiente, cambiar los datos necesarios y presionar Guardar cambios.

**Desactivar productos:** seleccionar Desactivar. El producto cambia a estado inactivo y deja de mostrarse en la tienda, pero permanece almacenado en la base de datos.

### 8. Cierre de sesión

Para finalizar el acceso administrativo, presionar el botón Cerrar sesión.

El sistema elimina los datos de la sesión y regresa a la pantalla de inicio de sesión.

### 9. Recomendaciones

- Mantener las credenciales de acceso privadas.
- Verificar los datos antes de registrar o modificar productos.
- Comprobar que los servicios de XAMPP estén activos cuando se utilice la versión local.
- Cerrar la sesión después de terminar las tareas administrativas.

### 10. Conclusión

ExplorandoWEB permite consultar información sobre tecnologías web y administrar los productos de una tienda mediante operaciones CRUD. Además, incorpora autenticación y manejo de sesiones para controlar el acceso al apartado administrativo.