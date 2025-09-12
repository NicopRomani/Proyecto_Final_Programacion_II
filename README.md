# Proyecto Final Programación II

Este es el repositorio del proyecto final desarrollado en PHP + MySQL.  
Incluye login de usuarios, gestión de vehículos y observaciones.

## Flujo de trabajo con Git (para colaboradores)

### Antes de empezar a programar
1. Asegurarse de estar en la rama principal:
   ```bash
   git checkout main
   ```
2. Bajar los cambios más recientes:
   ```bash
   git pull
   ```

### Mientras programás
- Hacé tus cambios en el código (PHP, CSS, SQL).
- Guardá los archivos normalmente.

### Cuando terminás de programar
1. Revisar qué cambiaste:
   ```bash
   git status
   ```
2. Preparar todos los cambios:
   ```bash
   git add .
   ```
3. Crear un commit con un mensaje descriptivo:
   ```bash
   git commit -m "Agregada función eliminar usuario"
   ```
4. Subir los cambios al repositorio:
   ```bash
   git push
   ```

## Buenas prácticas
- Siempre hacer **`git pull` antes de empezar** a trabajar.  
- Siempre hacer **`git push` al terminar**, para que el compañero tenga la última versión.  
- Usar mensajes de commit claros y breves:
  - `"Agregado registrar vehículo"`
  - `"Corregido bug en login"`
  - `"Aplicado CSS al formulario"`

---

## Estructura del proyecto
```
Proyecto_Final_v1/
│── css/
│   └── estilos.css
│── db.php
│── index.php
│── login.php
│── logout.php
│── register_user.php
│── register_vehicle.php
│── eliminar_usuario.php
│── eliminar_vehiculo.php
└── README.md
```

**Recordatorio**: mantener la DB `proyectofinal` actualizada.  

## Actualizar DB desde un pull mas nuevo
```
mysql -u root proyectofinal < "C:\Users\nicor\OneDrive\Documentos\Programacion II\2do_C\Proyecto_Final_v1\database\proyectofinal.sql"
//Esta es mi ruta original, pero tendria que ser la ruta de tu proyecto, dentro de la carpeta "database"
```