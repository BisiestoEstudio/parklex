# Checklist de verificación — Migración del sitio

Este documento es para ti, administrador/a de la web. Reúne, en lenguaje sencillo, las comprobaciones que puedes hacer para confirmar que cada funcionalidad traspasada al nuevo sitio (tema `parklex` + plugin `parklex-core`) funciona correctamente antes de dar por cerrada la migración.

Cada apartado corresponde a un bloque de funcionalidad. Los que aún no están rellenos se irán completando a medida que se cierre esa parte del proyecto.

---

## 1. WooCommerce (tienda)

_Pendiente de completar._

## 2. Roles y permisos

_Pendiente de completar._

## 3. Mi Cuenta — Presentations / Submit Documents

_Pendiente de completar._

## 4. Internal Projects

**Qué es:** un catálogo interno de proyectos de referencia, visible solo para las personas que tú autorices, con posibilidad de que ellas mismas añadan proyectos nuevos (quedan pendientes de tu aprobación antes de publicarse).

### Antes de probar

- [ ] Entra en **Internal Projects → Settings** (menú del admin) y guarda la página una vez, aunque no cambies nada — así se rellenan los textos por defecto (mensaje de envío correcto, texto del botón, etc.).
- [ ] Comprueba que al menos un usuario de prueba tiene activado el permiso **"Allow Internal Projects for user"** en su perfil (Usuarios → editar usuario). Sin este permiso, esa persona no puede ver ni usar esta sección.

### Acceso y permisos

- [ ] Con un usuario **sin** el permiso activado: al intentar entrar en la sección de Internal Projects, debe ser redirigido a "Mi cuenta" — no debe poder ver nada.
- [ ] Con un usuario **con** el permiso activado: en "Mi cuenta" debe aparecer un enlace **"Internal Projects"** en el menú lateral, y al pincharlo te lleva al listado de proyectos.
- [ ] Sin iniciar sesión (o en una ventana de incógnito): cualquier URL de esta sección debe redirigir también a "Mi cuenta".

### Enviar un proyecto nuevo

- [ ] Con el usuario permitido, entra en el formulario de "Nuevo proyecto" (botón desde el listado de Internal Projects).
- [ ] Sube varias fotos a la vez (selecciona más de un archivo de golpe en el selector de imágenes) y comprueba que todas se van colocando en la galería.
- [ ] Reordena las fotos arrastrándolas, y borra alguna con el botón de eliminar — comprueba que el cambio se refleja visualmente.
- [ ] Rellena el resto de campos obligatorios (nombre del proyecto, ciudad, país, tipo de producto, etc.) y pulsa el botón de envío.
- [ ] Confirma que aparece un mensaje de "enviado correctamente" y que el proyecto **no se publica automáticamente** — debe quedar pendiente de tu revisión.

### Revisar y aprobar (como administrador)

- [ ] En el menú de administración, comprueba que el elemento **"Internal Proj."** muestra un número en rojo indicando cuántos proyectos están pendientes de revisar.
- [ ] Entra en el listado de Internal Projects del admin, abre el proyecto recién enviado y revisa que las fotos, el título y el resto de datos se han guardado bien.
- [ ] Publícalo (cambia su estado a "Publicado").

### Ver el proyecto publicado

- [ ] Visita el listado público de Internal Projects y comprueba que el proyecto aprobado ya aparece.
- [ ] Prueba varios de los filtros del listado (país, tipo de producto, año, etc.) y confirma que filtran correctamente los resultados.
- [ ] Abre la ficha del proyecto: las fotos deben verse en una galería, y al hacer clic en una debe abrirse en grande (efecto "lightbox").
- [ ] Pulsa el botón de **"Descargar fotos"** y confirma que se descarga un archivo `.zip` con todas las imágenes del proyecto.

### Proyectos ya existentes (los que ya estaban antes de la migración)

- [ ] Abre la ficha de 2-3 proyectos que ya existían antes de esta migración (no creados por ti en las pruebas) y comprueba que su galería de fotos se ve correctamente, sin errores ni imágenes rotas.

---

## 5. Lunch & Learn

**Qué es:** los distribuidores piden organizar una charla formativa ("Lunch & Learn") para sus clientes; tú apruebas o rechazas la petición, y luego el distribuidor sube la factura del evento para que se la reembolséis. Ver la explicación completa en `docs/lunch-and-learn.md`.

### Antes de probar

- [ ] Entra en **Lunch & Learn requests → Settings** (menú del admin) y guarda la página una vez, aunque no cambies nada — así se rellenan los textos por defecto.
- [ ] Revisa esa misma pantalla de Settings: debe aparecer ya rellenado el listado de "Types of event" (Official CEU/AIA, Official CPD/RIBA con sus cursos, Lunch & Learn, Show) — es la configuración real que ya existía, migrada automáticamente. Si falta algo, avisa.
- [ ] Comprueba que al menos un usuario de prueba tiene activado el permiso **"Allow Lunch & Learn requests for user"** en su perfil (Usuarios → editar usuario).
- [ ] Comprueba que tienes configurado el envío de emails del sitio (por ejemplo con el plugin WP Mail SMTP ya instalado) para poder verificar los correos de este apartado.

### Acceso y permisos

- [ ] Con un usuario **sin** el permiso activado: no debe verse el enlace "Lunch & Learn requests" en el menú de Mi Cuenta, y la URL del formulario público debe redirigir a Mi Cuenta si se visita directamente.
- [ ] Con un usuario **con** el permiso activado: debe verse el enlace en Mi Cuenta y poder acceder al formulario.

### Crear una request (como distribuidor de prueba)

- [ ] Rellena el formulario público de petición (Firm, fecha, hora, ubicación, dirección, nº de asistentes esperados, tipo de evento, coste, comentarios).
- [ ] Elige un "Type of event" de los que **tienen cursos asociados** (Official CEU/AIA u Official CPD/RIBA) y comprueba que el campo cambia de "Name of the presentation" a un desplegable "Course name" con los cursos de ese tipo.
- [ ] Elige un "Type of event" **sin** cursos (Lunch & Learn o Show) y comprueba que vuelve a mostrarse el campo de texto libre "Name of the presentation".
- [ ] Envía el formulario y confirma que aparece el mensaje de "enviado correctamente" y desaparece el botón de enviar.
- [ ] Comprueba que te llega (a la cuenta de administrador configurada) el email de **"nueva petición creada"**.

### Revisar y aprobar/denegar (como administrador)

- [ ] En **Lunch & Learn requests** (menú del admin), localiza la request recién creada — revisa que las columnas nuevas (Request ID, User, Architectural/Interior Firm, Invoices, Complited, Date) muestran los datos correctos, y que se pueden ordenar pinchando en su cabecera.
- [ ] Ábrela y compueba los datos guardados. Publícala (botón "Publicar") para **aprobarla**.
- [ ] Confirma que el distribuidor recibe el email de **"petición aprobada"**.
- [ ] Crea una segunda request de prueba y esta vez pásala a **Borrador** en vez de publicarla, para **denegarla**. Confirma que el distribuidor recibe el email de **"petición denegada"**.

### Gestionar la request aprobada (Mi Cuenta, como distribuidor)

- [ ] Entra en "Lunch & Learn requests" desde Mi Cuenta: debe verse el listado con la request aprobada marcada como "Pending" (factura aún no enviada).
- [ ] Abre la ficha de la request aprobada. Añade 2-3 asistentes (nombre, apellido, email, nº de asociado, certificado, comentarios) con el botón "Add Attendee", y pulsa **"Save Attendee info"** — comprueba que no da error.
- [ ] Sube 1-2 ficheros de factura (una imagen y un PDF, por ejemplo) y comprueba que aparecen en la lista según se suben, sin recargar la página.
- [ ] Borra uno de los ficheros subidos con el botón de la "x" y comprueba que desaparece de la lista.
- [ ] Pulsa **"Send info"**, confirma el aviso, y comprueba que la página pasa a modo solo-lectura (ya no se pueden añadir/borrar asistentes ni ficheros).
- [ ] Comprueba que llega el email de **"factura enviada"** (con los ficheros adjuntos) a la cuenta de administrador.
- [ ] Vuelve al listado de Mi Cuenta: la request debe aparecer ahora como "Sent".

### Devolver una factura (como administrador)

- [ ] En wp-admin, abre esa misma request y desmarca la casilla **"Distributor approved invoices"**. Guarda.
- [ ] Comprueba que el distribuidor recibe el email de **"factura devuelta para corregir"**, con los ficheros adjuntos.

### Estadísticas (como administrador)

- [ ] Marca la casilla **"Complited"** en 2-3 requests ya publicadas (puedes usar peticiones antiguas reales, no hace falta que sean de prueba).
- [ ] Entra en **Lunch & Learn requests → Statistics**, elige un "Report type" (Per distributor / Per studios / Per assistants) y pulsa **"Generate report"** — debe verse una tabla con los datos.
- [ ] Pulsa **"Download report"** y abre el CSV descargado en Excel/Sheets — comprueba que los acentos y la "ñ" se ven bien (antes se rompían).

### Recordatorios automáticos

Estos avisos se disparan solos, sin que nadie tenga que hacer nada, pero tardan varios días en dispararse de forma natural (no es fácil de probar en el momento). Si quieres verificarlos sin esperar, pide a quien lleve el desarrollo que te ayude a adelantar la fecha del recordatorio de una request de prueba directamente en la base de datos:

- [ ] Recordatorio de **"sube tu factura"**: se envía al distribuidor unos días después de la fecha del evento (configurable en Settings) si no ha subido nada.
- [ ] Recordatorio de **"factura pendiente de revisar"**: se reenvía a los 10 días si tú no has procesado una factura ya enviada.

### Rol "Lunch & Learn Editor"

- [ ] Si tienes un usuario con el rol **Lunch & Learn Editor**, comprueba que puede entrar en wp-admin y gestionar las requests (aprobar/denegar), pero no ve el resto de secciones de gestión de la tienda.
