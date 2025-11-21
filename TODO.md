# TODO: Implement Notifications System

## Step 1: Update Database Schema
- [x] Add the `notificaciones` table to `database_schema.sql` with the specified fields.

## Step 2: Create Notification Utility Function
- [x] Create `utils/NotificationUtils.php` with the `crearNotificacion($db, $id_usuario, $tipo, $mensaje)` function.

## Step 3: Integrate into Existing Endpoints
- [x] Update `endpoints/respuestas.php` to call `crearNotificacion` when a new respuesta is posted (notify the consulta owner if different user).
- [x] Update `endpoints/votos.php` to call `crearNotificacion` when a new voto is registered (notify the respuesta owner if different user).

## Step 4: Create Notifications Endpoint
- Create `endpoints/notificaciones.php` to return all notifications for the logged-in user (using JWT auth).

## Step 5: Create Mark as Read Endpoint
- Create `endpoints/marcar_leida.php` to mark a notification as read, ensuring only the owner can update it.

## Step 6: Test and Verify
- [x] Run the database update script to apply schema changes.
- Test the endpoints for functionality and RBAC.
