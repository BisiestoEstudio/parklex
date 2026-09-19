# Lunch & Learn — Qué es y qué hace

## Qué es "Lunch & Learn"

Es un programa donde los **distribuidores** (usuarios con rol `distributor`, marcados individualmente con el permiso `allow_ll_request`) organizan charlas/presentaciones formativas ("Lunch & Learn") para sus clientes sobre los productos de Prodema, y la empresa les **reembolsa el coste del evento** (comida, etc.) contra factura.

## El flujo completo

1. **Petición** — El distribuidor, desde una página del sitio (`templates/request-lunchlearn.php`), rellena un formulario: fecha, hora, ubicación, tipo de evento/curso, nº de asistentes esperados, coste, proyecto relacionado, comentarios. Se crea una request en estado `pending` y se avisa al admin por email.

2. **Aprobación o rechazo** — Un administrador revisa la request en el wp-admin (es un CPT normal, `lunch_learn_request`) y la publica (aprueba) o la pasa a borrador (rechaza).
   - Si se aprueba → email al distribuidor avisando, y se programa un **recordatorio automático** para que suba la factura (a los N días).
   - Si se rechaza → email al distribuidor avisando.

3. **Día del evento** — El distribuidor, desde "Mi Cuenta → Lunch & Learn requests", entra en la ficha del evento aprobado y rellena la lista real de **asistentes** (nombre/apellido/email) que fueron.

4. **Justificación de gasto** — El distribuidor sube los **ficheros de la factura** (puede subir varios, borrar alguno) desde esa misma ficha, y pulsa "enviar". Esto dispara un email al admin con los ficheros adjuntos y marca la request como "aprobada por el distribuidor" (`distributor_approved`).
   - Si el admin, revisando la factura en el wp-admin, desmarca esa aprobación (porque algo está mal), se dispara un email de vuelta al distribuidor devolviéndole la factura para corregirla.
   - Si no sube nada a tiempo, hay un **segundo recordatorio automático** (a los 10 días) para que suba la factura.

5. **Estadísticas** — Hay una página de opciones en el admin ("Lunch & Learn Statistics") pensada para generar un informe (por distribuidor, por curso, o por asistentes) y descargarlo en CSV. **Esto parece no funcionar realmente**: depende de un dato (`complited_lrequest`, "request completada") que ningún sitio del código llega a marcar, así que probablemente el informe siempre sale vacío.

## Roles que intervienen

- **Distribuidor** — crea la request, sube asistentes/factura. Solo puede hacerlo si tiene marcado `allow_ll_request`.
- **Administrador / Editor** — aprueba/rechaza, gestiona factura, ve estadísticas.
- **`lunch_learn_editor`** — un rol pensado para gestionar solo esto sin acceso completo de admin, pero su creación está desactivada en el código (nunca se llegó a activar, o se creó a mano en algún momento).

Es, en esencia, un mini-CRM de solicitudes con aprobación en dos fases (evento → factura) y notificaciones por email en cada paso, más una capa de reporting que parece rota.
