# EV-RD-System

**EV-RD-System** es un sistema de registro de ingresos y egresos diseñado en PHP con un enfoque minimalista y responsivo. Su principal objetivo es facilitar la gestión de registros por usuarios y administradores, ofreciendo funcionalidades dinámicas e intuitivas.

---

## Características Principales

- **Gestión de Roles de Usuario:**
  - **Administrador:** Control total del sistema, incluyendo la gestión de usuarios y revisión de registros.
  - **Usuario:** Permite registrar ingresos y egresos dentro de un horario definido por el administrador.

- **Registros de Ingresos y Egresos:**
  - Creación, edición, eliminación y visualización de registros.
  - Validación del estado de los registros como correctos o incorrectos.

- **Interfaz Responsiva:**
  - Diseño adaptativo para dispositivos móviles y de escritorio.
  - Desarrollado con Bootstrap para asegurar una experiencia de usuario moderna y fluida.

- **Interacción Dinámica:**
  - Uso de AJAX para realizar operaciones sin recargar la página, mejorando la usabilidad.

---

## Requisitos del Sistema

Para ejecutar EV-RD-System, se requiere:

- Un servidor con soporte para:
  - PHP (versión 7.4 o superior).
  - MySQL (versión 5.7 o superior).
- Un navegador moderno (Google Chrome, Firefox, Edge).
- Composer para la gestión opcional de dependencias (recomendado).
- Acceso a un servidor web como Apache o Nginx.

---

## Instalación

Sigue estos pasos para configurar y desplegar el sistema:

1. **Base de Datos:**
   - Crea una base de datos llamada `rdsys`.
   - Importa el archivo `database.sql` proporcionado en el repositorio para generar las tablas necesarias.

2. **Configuración:**
   - Configura las credenciales de conexión a la base de datos en el archivo correspondiente.

3. **Servidor Web:**
   - Asegúrate de que el directorio público del proyecto esté configurado como raíz del servidor web.

4. **Dependencias (Opcional):**
   - Si utilizas Composer, instala las dependencias necesarias para el proyecto.

5. **Pruebas:**
   - Accede al sistema desde tu navegador web utilizando la URL configurada.

---

## Licencia

Este proyecto está bajo la [Licencia MIT](https://opensource.org/licenses/MIT), lo que permite su uso, modificación y distribución libremente.

---

## Contribuciones

Las contribuciones al proyecto son bienvenidas. Si deseas colaborar, sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama para tus cambios.
3. Envía un Pull Request describiendo tus modificaciones.

---

**Autor:**  
[Espacios Virtuales]  
(https://espaciosvirtuales.cl)

**Repositorio Oficial:**  
[GitHub - EV-RD-System](https://github.com/dutreras369/ev-rd-system)
