# 📚 Lectio - E-commerce de Literatura Premium

![Lectio Logo](public/img/logo.webp)

Lectio es una plataforma de comercio electrónico desarrollada en **Laravel** diseñada para ofrecer una experiencia de compra de libros de nivel premium. Centrada en la usabilidad, el diseño elegante y la conversión, Lectio combina una estética refinada con funcionalidades avanzadas como búsqueda asíncrona, fidelización de clientes y gestión inteligente de inventario.

---

## ✨ Características Principales

### 🛍️ Experiencia de Usuario (UX) y Tienda
* **Catálogo Dinámico:** Filtrado avanzado por género, búsqueda por palabras clave y rango de precios.
* **Buscador Inteligente (Live Search):** Resultados instantáneos en la barra de navegación sin recargar la página (AJAX).
* **Fichas de Producto Detalladas:** Soporte para múltiples formatos (Físico, E-book, Audiolibro), valoraciones, sinopsis y cross-selling automático ("También te puede interesar").
* **Lista de Deseos (Wishlist):** Gestión asíncrona de libros favoritos directamente desde el catálogo y las fichas de producto.
* **Carrito y Checkout Fluido:** Añadir al carrito sin recargar la página, cálculo automático de envíos (gratis a partir de 30€) y vaciado rápido con confirmación de seguridad.
* **Pasarela de Pago Segura:** Integración nativa con **Stripe** para procesar tarjetas de crédito/débito.

### 🎁 Fidelización y Marketing
* **Puntos Lectio:** Sistema de recompensas donde 1€ gastado equivale a 1 punto. Los usuarios pueden canjear 100 puntos por un descuento del 5%.
* **Descuento de Bienvenida:** Cupón de un solo uso por usuario.
* **Cupones Seguros:** Los cupones generados por puntos están vinculados a la cuenta del usuario que los canjea (intransferibles).

### ⚙️ Panel de Administración (Backoffice)
* **Dashboard Analítico:** Resumen de ventas, beneficios totales y libros más vendidos.
* **Gestión de Inventario (CRUD):** Creación, edición y eliminación de libros, stock y formatos con control de ofertas (precio original tachado).
* **Gestión de Pedidos:** Listado detallado de transacciones con opción de ver facturas e historial de clientes.
* **Bandeja de Soporte:** Sistema de tickets integrado para responder a las consultas de los clientes directamente desde el panel.

---

## 🛠️ Tecnologías y Arquitectura

* **Backend:** Laravel 11.x (PHP 8.2+)
* **Base de Datos:** MySQL (Gestión mediante Eloquent ORM y Migrations)
* **Frontend:** Blade Templating Engine + Tailwind CSS (Diseño responsive y moderno)
* **Interactividad:** Vanilla JavaScript + AJAX (Fetch API) + Alpine.js (opcional)
* **Pagos:** Stripe PHP SDK
* **Alertas:** SweetAlert2 (Notificaciones personalizadas e integradas)

---

## 🚀 Requisitos Previos

Antes de instalar Lectio, asegúrate de tener instalado en tu entorno:

* PHP >= 8.2
* Composer
* Node.js y npm (para compilar los assets de Tailwind)
* Servidor MySQL o MariaDB
* Una cuenta de [Stripe](https://stripe.com/) (para obtener las claves de API)

---

## 📥 Instalación

Sigue estos pasos para desplegar el proyecto en tu máquina local:

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/tu-usuario/lectio.git
   cd lectio
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Frontend:**
   ```bash
   npm install
   ```

4. **Configurar el entorno:**
   Copia el archivo de ejemplo y configura tus variables.
   ```bash
   cp .env.example .env
   ```
   *Abre el archivo `.env` y configura tu base de datos y tus claves de Stripe:*
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lectio_db
   DB_USERNAME=root
   DB_PASSWORD=

   STRIPE_KEY=pk_test_tu_clave_publica_aqui
   STRIPE_SECRET=sk_test_tu_clave_privada_aqui
   ```

5. **Generar la clave de la aplicación:**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar las migraciones y seeders:**
   *(Asegúrate de que la base de datos configurada en el `.env` existe)*
   ```bash
   php artisan migrate --seed
   ```

7. **Compilar los assets (Tailwind CSS):**
   ```bash
   npm run build
   ```
   *(Si estás desarrollando activamente, usa `npm run dev`)*

8. **Levantar el servidor local:**
   ```bash
   php artisan serve
   ```
   El proyecto estará disponible en: `http://localhost:8000`

---

## 🛡️ Seguridad y Pruebas

El proyecto incluye medidas de seguridad estándar de la industria implementadas a través de Laravel:
* Prevención contra inyecciones SQL mediante Eloquent ORM.
* Protección CSRF en todos los formularios.
* Saneamiento de salidas Blade (prevención XSS).
* Bloqueo de asignación masiva (Mass Assignment) restringido con `$fillable`.
* Rutas de administración protegidas por Middleware de roles.

*(Para más detalles sobre los casos de prueba ejecutados, consultar la memoria del proyecto, apartado 4.2 Pruebas).*

---

## 📧 Soporte

Si encuentras algún problema o tienes alguna sugerencia para mejorar Lectio, por favor abre un _issue_ en este repositorio o contacta con el administrador del sistema.

---

*Desarrollado con ❤️ para los amantes de la lectura.*
