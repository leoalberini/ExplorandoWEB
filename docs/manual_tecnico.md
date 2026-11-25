# MANUAL TÉCNICO
## Proyecto ExplorandoWEB

**Actividad:** ADA 6. Proyecto Web con CRUD, base de datos y autenticación  
**Tecnologías:** PHP, MySQL/MariaDB, HTML5, CSS3 y JavaScript.  
**Entorno de desarrollo:** Visual Studio Code y XAMPP.

---

### 1. Introducción

El proyecto ExplorandoWEB es un sitio web educativo desarrollado para presentar información relacionada con la historia y las tecnologías de la Web.

Como parte de la ADA 6, se incorporaron funcionalidades del lado del servidor utilizando PHP y una base de datos relacional para administrar productos mediante operaciones CRUD. También se implementó un sistema de autenticación de usuarios utilizando sesiones para proteger el apartado administrativo.

### 2. Requisitos técnicos

Para ejecutar el proyecto localmente se requieren los siguientes componentes:

- **XAMPP:** entorno de servidor local.
- **Apache:** servidor web encargado de ejecutar las solicitudes.
- **PHP:** lenguaje utilizado para implementar la lógica del servidor.
- **MariaDB/MySQL:** sistema gestor de bases de datos.
- **phpMyAdmin:** herramienta para administrar la base de datos.
- **Visual Studio Code:** editor utilizado para desarrollar el proyecto.
- **Navegador web:** utilizado para acceder al sitio.

Versiones identificadas en el entorno utilizado durante el desarrollo:

- PHP 8.3.36.
- MariaDB 10.11.16.
- phpMyAdmin 5.2.3.

### 3. Ubicación del proyecto

El proyecto se encuentra dentro del directorio de XAMPP:

`C:\xampp\htdocs\ExplorandoWEB`

Su acceso local se realiza mediante:

`http://localhost/ExplorandoWEB/`

Los archivos se organizan en la carpeta principal y en subcarpetas, como `css`, donde se encuentra la hoja de estilos `estilos.css`.

### 4. Estructura de archivos principales

Los siguientes archivos contienen las funcionalidades principales implementadas:

| Archivo | Descripción |
|---|---|
| `conexion.php` | Establece la conexión con la base de datos. |
| `tienda.php` | Consulta y muestra los productos activos. |
| `carrito.php` | Presenta el contenido del carrito y calcula los totales. |
| `carrito_accion.php` | Procesa las acciones del carrito. |
| `login.php` | Permite iniciar sesión mediante correo y contraseña. |
| `auth.php` | Comprueba la autenticación y el rol de administrador. |
| `logout.php` | Cierra la sesión del usuario. |
| `admin_productos.php` | Presenta el panel administrativo y consulta los productos. |
| `crear_producto.php` | Registra nuevos productos. |
| `editar_producto.php` | Modifica los datos de productos existentes. |
| `eliminar_producto.php` | Desactiva productos sin eliminarlos permanentemente. |
| `css/estilos.css` | Contiene los estilos visuales del sitio. |

El archivo `crear_admin.php` se utilizó para registrar inicialmente al administrador y posteriormente se eliminó por seguridad.

### 5. Conexión con la base de datos

La conexión se establece mediante la extensión MySQLi de PHP.

El archivo `conexion.php` contiene los parámetros de conexión:

- Servidor: `localhost`.
- Usuario: `root`.
- Contraseña: vacía en el entorno local configurado.
- Base de datos: `explorandoweb`.

Se utiliza el método `set_charset("utf8mb4")` para configurar la codificación de caracteres.

La conexión es reutilizada por los diferentes scripts mediante `require_once`, evitando repetir su configuración.

**Nota:** al publicar el proyecto en un servidor externo, deben sustituirse estos parámetros por las credenciales proporcionadas por el alojamiento.

### 6. Estructura de la base de datos

La base de datos utilizada se llama `explorandoweb` y contiene tres tablas identificadas:

- `contactos`.
- `productos_ada5`.
- `usuarios`.

#### 6.1 Tabla productos_ada5

Almacena los productos disponibles en la tienda.

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | INT | Identificador único y autoincremental. |
| codigo | VARCHAR(30) | Código único del producto. |
| nombre | VARCHAR(100) | Nombre del producto. |
| descripcion | VARCHAR(255) | Descripción del producto. |
| precio | DECIMAL(10,2) | Precio del producto. |
| activo | TINYINT(1) | Estado del producto. |

El campo `activo` permite distinguir entre productos disponibles e inactivos.

#### 6.2 Tabla usuarios

Almacena la información necesaria para identificar a los usuarios del sistema.

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | INT | Identificador único. |
| nombre | VARCHAR(100) | Nombre del usuario. |
| correo | VARCHAR(150) | Correo electrónico único. |
| password_hash | VARCHAR(255) | Hash de la contraseña. |
| rol | ENUM | Identifica al administrador o usuario. |
| activo | TINYINT(1) | Estado de la cuenta. |
| creado_en | TIMESTAMP | Fecha de creación. |

El campo `rol` permite diferenciar entre administradores y usuarios convencionales.

#### 6.3 Tabla contactos

Se conserva la tabla `contactos`, utilizada por la funcionalidad del formulario de contacto del proyecto. Su estructura corresponde a la implementación anterior.

### 7. Implementación de las operaciones CRUD

Las operaciones CRUD se implementaron sobre la tabla `productos_ada5`.

**CREATE – Registrar**

El archivo `crear_producto.php` recibe los datos de un formulario y realiza una instrucción SQL `INSERT` para almacenar un producto nuevo.

Antes de guardar, valida los campos obligatorios, sus longitudes y el formato del precio.

**READ – Consultar**

El archivo `admin_productos.php` utiliza una consulta `SELECT` para recuperar los productos registrados, incluyendo los activos e inactivos.

La información se muestra mediante una tabla HTML.

Por otra parte, `tienda.php` consulta únicamente los productos cuyo campo `activo` tiene valor 1.

**UPDATE – Modificar**

El archivo `editar_producto.php` permite recuperar un producto mediante su identificador y modificar sus datos.

Utiliza una instrucción SQL `UPDATE` con una condición `WHERE id = ?`.

**DELETE – Desactivar**

El archivo `eliminar_producto.php` realiza una desactivación lógica mediante la instrucción:

`UPDATE productos_ada5 SET activo = 0 WHERE id = ? AND activo = 1`

De esta manera, el registro permanece almacenado en la base de datos, pero deja de mostrarse en la tienda.

### 8. Autenticación y manejo de sesiones

La autenticación se implementó utilizando sesiones de PHP.

El proceso funciona de la siguiente manera:

1. El usuario accede a `login.php`.
2. Introduce su correo electrónico y contraseña.
3. El sistema busca el correo en la tabla `usuarios`.
4. PHP utiliza `password_verify()` para comprobar la contraseña con el hash almacenado.
5. Cuando las credenciales son correctas, se genera un nuevo identificador de sesión mediante `session_regenerate_id(true)`.
6. Se almacenan en la sesión el identificador, nombre, correo y rol del usuario.
7. El administrador es redirigido al panel administrativo.

Para proteger las páginas se utiliza el archivo `auth.php`, que comprueba que exista un identificador de usuario en la sesión y que el rol corresponda a `admin`.

El archivo `logout.php` elimina los datos de la sesión, elimina la cookie de sesión y destruye la sesión activa.

### 9. Medidas de seguridad implementadas

Durante el desarrollo se incorporaron las siguientes medidas:

- Uso de `password_hash()` y `password_verify()` para proteger las contraseñas.
- Consultas preparadas mediante `prepare()` y `bind_param()`.
- Validación de los datos recibidos mediante formularios.
- Uso de `htmlspecialchars()` para mostrar información sin interpretar contenido HTML ingresado por usuarios.
- Protección de las páginas administrativas mediante sesiones y comprobación de roles.
- Uso de tokens CSRF en los formularios administrativos de registro, edición y desactivación, así como en el cierre de sesión.
- Validación de los identificadores de productos antes de procesar operaciones.

### 10. Procedimiento para ejecutar el sistema

Para ejecutar el proyecto en un entorno local:

1. Instalar y abrir XAMPP.
2. Iniciar Apache y MySQL.
3. Colocar la carpeta `ExplorandoWEB` dentro de `htdocs`.
4. Crear o importar la base de datos `explorandoweb` con sus tablas.
5. Configurar los parámetros de conexión en `conexion.php`.
6. Abrir un navegador.
7. Ingresar a `http://localhost/ExplorandoWEB/`.

Para ingresar al apartado administrativo se debe utilizar el correo y la contraseña del administrador registrado en la tabla `usuarios`.

### 11. Pruebas realizadas

Durante el desarrollo se realizaron pruebas funcionales para comprobar:

- Consulta de productos almacenados.
- Registro de nuevos productos.
- Modificación de productos existentes.
- Desactivación de productos.
- Desaparición de productos inactivos de la tienda.
- Inicio de sesión con credenciales registradas.
- Cierre de sesión.
- Redirección al inicio de sesión cuando se intenta acceder al panel sin autenticación.
- Funcionamiento del carrito de compras después de incorporar las nuevas funcionalidades.

Las pruebas locales permitieron comprobar el funcionamiento de las características incorporadas sin afectar las funciones anteriores de la tienda.

### 12. Mantenimiento y recomendaciones

Para mantener el funcionamiento del proyecto se recomienda:

- Realizar respaldos periódicos de la base de datos.
- Mantener actualizados PHP y el sistema gestor de bases de datos.
- Verificar los parámetros de conexión antes de publicar el sitio.
- Mantener protegidas las credenciales de acceso.
- Revisar los datos antes de modificar productos.
- Utilizar credenciales seguras para los usuarios.
- Configurar adecuadamente los permisos y las conexiones seguras en el servidor de alojamiento.

### 13. Conclusión

La incorporación de PHP y MariaDB permitió ampliar las funcionalidades de ExplorandoWEB mediante la implementación de operaciones CRUD y autenticación de usuarios utilizando sesiones.

La organización de los scripts en archivos independientes facilita el mantenimiento del código y permite identificar las responsabilidades de cada componente.

El resultado es una aplicación web dinámica que conserva las funcionalidades anteriores de la tienda e incorpora herramientas administrativas para gestionar la información de los productos.