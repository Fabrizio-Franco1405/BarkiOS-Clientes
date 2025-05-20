# 👔 BarkiOS - Sistema de Gestión de Clientes (Docker + XAMPP)  

![Docker](https://img.shields.io/badge/Docker-✓-blue?logo=docker)  
![XAMPP](https://img.shields.io/badge/XAMPP-Compatible-FB7A24?logo=xampp)  
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)  
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql)  

## 📌 Tabla de Contenidos  
- [Descripción](#-descripción)  
- [Funcionalidades](#-funcionalidades)  
- [Tecnologías](#-tecnologías)  
- [Instalación con Docker](#-instalación-con-docker)  
- [Instalación con XAMPP](#-instalación-con-xampp)  
- [Estructura](#-estructura-del-proyecto)  
- [Uso](#-uso)  
- [API](#-api)  
- [Licencia](#-licencia)  

## 🌟 Descripción  
**Módulo de Clientes de BarkiOS** es un sistema completo para la gestión de clientes en tiendas de ropa con soporte para ambos entornos:  

- 🐳 **Docker**: Entorno containerizado listo para producción  
- 🛠️ **XAMPP**: Configuración local para desarrollo rápido  

## 🚀 Funcionalidades  
| Módulo | Descripción |  
|--------|------------|  
| **Registro** | Alta de clientes con validación de RIF/DNI |  
| **Gestión** | Edición de datos de contacto y productos |  
| **Reportes** | Generación de listados y transacciones |  
| **Integración** | API REST para conexión con otros sistemas |  

## 🛠️ Tecnologías  
```plaintext
Backend: PHP 8.2 + Composer  
Frontend: Bootstrap 5 + Vanilla JS  
Base de datos: MySQL 8.0  
Entornos: Docker (producción) | XAMPP (desarrollo)  
Herramientas: phpMyAdmin (ambos entornos)  
```  

## 🛠️ Instalación con XAMPP  

### Requisitos  
- XAMPP 8.2+  
- MySQL 8.0  

### Pasos  
1. Clonar rama xampp:  
   ```bash
   git clone -b xampp https://github.com/Fabrizio-Franco1405/BarkiOS-Clientes.git
   ```  
2. Mover proyecto a `htdocs`  
3. Importar DB:  
   ```sql
   source database/clientes.sql
   ```  
4. Configurar `config/database.php`  

5. Elegir ruta:
   ```
   http://localhost/BarkiOS-Clientes/app/views/admin/clients-admin.php
   ```

## 🐳 Instalación con Docker  

### Requisitos  
```powershell
docker --version
docker-compose --version
```

### Pasos  
1. Clonar repositorio (rama main):  
   ```bash
   git clone https://github.com/Fabrizio-Franco1405/BarkiOS-Clientes.git
   ```  
2. Iniciar contenedores:  
   ```powershell
   docker-compose up -d --build
   ```  
3. Elegir ruta:
   ```
   http://localhost:9080/app/views/admin/clients-admin.php
   ```

## 🛠️ Instalación con XAMPP  

### Requisitos  
- XAMPP 8.2+  
- MySQL 8.0  

### Pasos  
1. Clonar rama xampp:  
   ```bash
   git clone -b xampp https://github.com/Fabrizio-Franco1405/BarkiOS-Clientes.git
   ```  
2. Mover proyecto a `htdocs`  
3. Importar DB:  
   ```sql
   source database/clientes.sql
   ```  
4. Configurar `config/database.php`  

## 📂 Estructura del Proyecto  
```bash
BarkiOS/  
├── app/  
│   ├── Controllers/  
│   ├── Models/  
│   └── Views/  
├── config/  
├── docker/  
├── public/  
└── xampp-config/    # Configs específicas para XAMPP  
```  

## 🖥️ Uso  
**Accesos Docker:**  
```plaintext
URL: http://localhost:8080/clientes 
phpMyAdmin: http://localhost:8000  
```  

**Accesos XAMPP:**  
```plaintext
URL: http://localhost/BarkiOS-Clientes/app/views/admin/clients-admin.php
phpMyAdmin: http://localhost/phpmyadmin  
```  

## 🌐 API Endpoints  
```plaintext
GET    /api/clientes     - Listar clientes  
POST   /api/clientes     - Crear nuevo clientes  
GET    /api/clientes/{id} - Detalles de clientes  
```  

## 📜 Licencia  
MIT License - Ver [LICENSE](LICENSE)  

---  
**Notas:**  
- Para desarrollo local usar rama `xampp`  
- Para entornos productivos usar rama `main` con Docker  
- Los datos de prueba se incluyen en `database/seeders`  

*Sistema desarrollado por [Tu Nombre] para [Nombre de la Institución]*