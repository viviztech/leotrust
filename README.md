# Leo Trust (Leo Foundation)

Leo Trust is a web platform for the Leo Foundation, a non-governmental organization (NGO) dedicated to empowering lives through de-addiction, rehabilitation, and social welfare services.

## Features

- **Bilingual Support:** Full support for English and Tamil languages.
- **Content Management:** Manage campaigns, success stories, and blog posts.
- **Donation System:** Integrated donation flow for supporting various causes.
- **Dynamic SEO:** Configurable SEO settings for better visibility.
- **Responsive Design:** Modern, mobile-friendly UI built with Tailwind CSS.

## Tech Stack

- **Framework:** [Laravel](https://laravel.com)
- **Frontend:** [Livewire](https://livewire.laravel.com), [Alpine.js](https://alpinejs.dev), [Tailwind CSS](https://tailwindcss.com)
- **Database:** MySQL
- **Asset Bundling:** Vite

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/viviztech/leotrust.git
   cd leotrust
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   Copy the example environment file and configure your database settings.
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database credentials and application settings.

4. **Generate App Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Serve the Application**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`.

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/amazing-feature`).
3. Commit your changes (`git commit -m 'Add some amazing feature'`).
4. Push to the branch (`git push origin feature/amazing-feature`).
5. Open a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
