# Lectio - Boutique Literaria Digital 📚✨

Lectio es una plataforma de comercio electrónico premium diseñada para amantes de la lectura. Se trata de una "boutique" digital que combina un diseño minimalista y elegante con una arquitectura de software robusta, segura y de alto rendimiento.

## 🚀 Características Principales

- **Autenticación Dual:** Registro tradicional y Login Social integrado con **Google (Laravel Socialite)**.
- **Buscador Inteligente:** Sistema de sugerencias en tiempo real mediante API asíncrona.
- **Rendimiento Optimizado:** Uso de **Redis** para la gestión de caché y colas de procesos (envío de correos).
- **Pasarela de Pagos Segura:** Integración completa con **Stripe** para transacciones encriptadas.
- **Carrito Blindado:** Lógica avanzada de validación de stock físico, prevención de cantidades negativas y persistencia en base de datos para usuarios registrados.
- **Biblioteca Personal:** Espacio privado para gestionar adquisiciones y marcar libros favoritos.
- **Panel Administrativo:** Gestión integral de inventario, pedidos y soporte técnico con control de acceso por roles (Middleware).
- **Diseño Responsive:** Interfaz adaptativa optimizada para dispositivos móviles y escritorio.

## 🛠️ Stack Tecnológico

- **Backend:** Laravel 11
- **Frontend:** Tailwind CSS & Blade components
- **Base de Datos:** MySQL
- **Caché y Colas:** Redis
- **Pagos:** Stripe API
- **Testing:** Pest Framework

## ⚙️ Instalación y Configuración

Sigue estos pasos para desplegar Lectio en tu entorno local:

1. **Clonar el repositorio:**
```bash
git clone [https://github.com/CesarBE0/Lectio.git](https://github.com/CesarBE0/Lectio.git)
cd Lectio
```

2. **Instalar dependencias de PHP:**
```bash
composer install
```

3. **Instalar y compilar dependencias de Frontend:**
```bash
npm install
npm run build
```

4. **Configurar el entorno:**
   Crea una copia del archivo `.env` y configura tus credenciales (DB, Redis, Stripe, Google Socialite):
```bash
cp .env.example .env
php artisan key:generate
```

5. **Ejecutar migraciones y seeders:**
```bash
php artisan migrate --seed
```

6. **Iniciar el servidor y las colas:**
```bash
php artisan serve
# En otra terminal para los correos:
php artisan queue:work
```

## 🧪 Pruebas Automatizadas (Testing)

Lectio cuenta con una suite de pruebas de alta cobertura que garantiza la integridad del sistema. Se han implementado 17 módulos de prueba que ejecutan 39 comprobaciones automáticas de seguridad y lógica de negocio.

Para ejecutar los tests, utiliza el framework **Pest**:
```bash
php artisan test
```

**Módulos verificados:**
- ✅ Registro y Login (Seguridad anti-inyección SQL)
- ✅ Protección CSRF en procesos críticos
- ✅ Gestión de stock e integridad del carrito
- ✅ Lógica de gastos de envío dinámicos
- ✅ Control de acceso administrativo (Roles)
- ✅ Interacciones AJAX (Wishlist y Favoritos)

## 👤 Autor

**César** - [CesarBE0](https://github.com/CesarBE0)

---
Proyecto desarrollado como Trabajo de Fin de Grado (TFG). 🎓
