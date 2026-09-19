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

_Funcionalidad aún no migrada — pendiente de iniciar._
