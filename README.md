## About worldbuildingapp

## Descripción

WorldbuildingApp es una eimplementación mediante laravel de la aplicación Aeberion diseñada para ayudar a los creadores de mundos, jugadores de rol o escritores a organizar y gestionar sus ideas, personajes, lugares y eventos. Esta aplicación proporciona una interfaz intuitiva y herramientas poderosas para construir y expandir universos ficticios de manera eficiente.

## Características

- **Gestión de Personajes**: Crea y administra personajes con detalles como nombre, descripción, personalidad, historia, etc.
- **Gestión de Países**: Crea y administra personajes con detalles como nombre, descripción, cultura, religión, militar, historia, etc.
- **Gestión de Lugares**: Organiza y describe los lugares de tu mundo, incluyendo detalles geográficos.
- **Gestión de Conflictos**: Describe conflictos del mundo como, nombre, fechas, batallas, bandos enfrentados, personajes relevantes, etc.
- **Eventos y Cronología**: Mantén un registro de eventos importantes y crea una cronología detallada de tu mundo.
- **Interfaz Intuitiva**: Diseño moderno y fácil de usar, perfecto para escritores, jugadores de rol y creadores de mundos.

## Tecnologías Utilizadas

- **Frontend**: HTML, CSS, JavaScript, Bootstrap, Toastr
- **Backend**: PHP, Laravel
- **Base de Datos**: MySQL
- **Autenticación**: Laravel Auth
- **Otros**: Composer, npm

## Instalación

### Requisitos Previos

- PHP 7.3 o superior
- Composer
- Node.js y npm
- MySQL

### Pasos de Instalación

1. **Clonar el Repositorio**

    ```bash
    git clone https://github.com/Gheryon/worldbuildingapp.git
    cd worldbuildingapp
    ```

2. **Instalar Dependencias**

    ```bash
    composer install
    npm install
    ```

3. **Configurar la Base de Datos**

    Copia el archivo `.env.example` a `.env` y configura tus credenciales de base de datos:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=worldbuildingapp
    DB_USERNAME=root
    DB_PASSWORD=secret
    ```

4. **Migrar la Base de Datos**

    ```bash
    php artisan migrate
    ```

5. **Compilar Activos**

    ```bash
    npm run dev
    ```

6. **Iniciar el Servidor**

    ```bash
    php artisan serve