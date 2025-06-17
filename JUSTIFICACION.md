# Justificación de Decisiones Técnicas

## 1. Elección de Laravel

- **Robustez y seguridad:** Laravel ofrece un framework sólido, seguro y ampliamente documentado.
- **Ecosistema:** Permite usar migraciones, seeders, factories y un ORM potente (Eloquent).
- **Productividad:** Facilita el desarrollo rápido y organizado.

## 2. Base de datos MySQL

- **Compatibilidad:** MySQL es ampliamente soportado y fácil de integrar con Laravel.
- **Facilidad de pruebas:** Permite usar datos de ejemplo y consultas eficientes.

## 3. Frontend: Blade + Tailwind + SweetAlert

- **Blade:** Permite vistas limpias y reutilizables.
- **Tailwind CSS:** Facilita un diseño moderno, responsive y personalizable.
- **SweetAlert2:** Mejora el feedback visual y la experiencia de usuario con modales atractivos.

## 4. Experiencia de usuario

- **Feedback inmediato:** Uso de modales y badges para informar al usuario en tiempo real.
- **Responsive:** El sistema se adapta a móvil, tablet y escritorio.
- **Accesibilidad:** Colores con buen contraste y navegación sencilla.

## 5. Organización y buenas prácticas

- **Código limpio:** Separación clara de lógica, vistas y estilos.
- **Estilos centralizados:** Uso de `custom.css` para personalizaciones.
- **Validaciones robustas:** Tanto en backend como en frontend.

## 6. Seguridad

- **Protección CSRF:** Todas las rutas usan tokens CSRF.
- **Validación de datos:** Se controla la entrada de usuario para evitar errores y ataques.
- **Validaciones robustas:** Tanto en backend como en frontend.

## 7. Datos de prueba
- **Factories y seeders:** Permiten poblar la base de datos fácilmente para pruebas y demos.

---