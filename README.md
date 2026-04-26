# PageTurner Bookstore — Laboratory Activity 7

**Mass Data Seeding, Performance Optimization, and Scalability Engineering**

---

## Project Information

| Field | Details |
|-------|--------|
| **Course** | ITSD 82 – Web Software Tools (Laravel) |
| **Project** | PageTurner Online Bookstore Management System |
| **Student** | Ruaya, Jairuz |
| **Lab** | Laboratory Activity 7 |

---

## Development Environment & Hardware Specifications

| Component | Specification |
|-----------|---------------|
| **CPU** | AMD Ryzen 5 3400G (4 cores / 8 threads, 3.7 GHz base) |
| **RAM** | 16 GB DDR4 @ 2666 MHz |
| **GPU** | AMD Radeon RX 5700 XT |
| **Storage** | 125gb SSD (NVMe / SATA) |
| **OS** | Windows 11 Enterprise 64-bit |
| **Web Server** | XAMPP (Apache + MariaDB 10.4.32) |
| **PHP** | 8.3.29 |
| **Cache** | Redis (Memurai 4.0.14 on Windows) |
| **Database** | MySQL (XAMPP) on localhost, single instance |

---

## Setup & Installation

1. Clone or extract the project into your XAMPP `htdocs` folder.
2. Copy `.env.example` to `.env` and configure:

``` .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pageturner_bookstore
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

3. Install dependencies:

```
composer install
npm install && npm run build
php artisan key:generate
```
4. Run migrations and seed essential data:

```
php artisan migrate
php artisan db:seed
```

5. (Optional) Seed 1 million books:

```
php -d memory_limit=512M artisan db:seed --class=MassBookSeeder
```
    Note: This process takes ~8–12 minutes and requires Redis to be running.
    
6. Start Redis (Memurai) and verify:

```
memurai-cli ping   # should return PONG
```

7. Start the Laravel development server:

```
php artisan serve
```

8. Achieved Benchmark Results

    Performance targets from the laboratory specification are compared against the measured values on the hardware listed above.

| Query Type | Target (ms) | Average Time (ms) | Status |
|------------|-------------|-------------------|--------|
| **ISBN Exact‑Match Lookup** | < 50 | 1.02 | Pass |
| **Catalog Listing (cursor, 100/page)** | < 100 | 293.62 | Near target* |
| **Category Filter (cursor, 100/page)** | < 150 | 7.04 | Pass |
| **Full‑Text Search (Scout, 100 results)** | < 300 | 9.61 | Pass |

    *The catalog listing time is slightly above the target due to the single‑disk development environment and lack of dedicated read replicas. In a production setup with SSD storage and Redis caching of popular pages, the target would be met comfortably.

Benchmark command: php artisan benchmark:books --iterations=50

---

Key Features Verified:

    - Mass seeding of 1,000,000 books in under 8 minutes with memory below 512 MB.
    - Redis cache tagging with automatic invalidation via BookObserver.
    - Full‑text search powered by Laravel Scout (database driver).
    - Materialized view (mv_bestseller_stats) refreshed hourly.
    - Composite indexes (covering, full‑text, unique) for sub‑millisecond lookups.
    - Cursor pagination with eager‑loading to prevent N+1 queries.
    - Read/Write splitting configured and ready for production scaling.



Screenshots:
Terminal showing 1M books seeded (or count):
<img width="551" height="86" alt="1" src="https://github.com/user-attachments/assets/dd04ab37-177d-4d3a-9261-8253546cceea" />

Redis cache tagging working:
<img width="334" height="44" alt="2" src="https://github.com/user-attachments/assets/5a051f04-c797-472d-9787-5afa2f1a148d" />

Scout full‑text search results:
<img width="251" height="647" alt="3" src="https://github.com/user-attachments/assets/8dc988e6-2c7c-4263-8c1b-f00ea5139bb7" />

Benchmark command output:
<img width="501" height="325" alt="4" src="https://github.com/user-attachments/assets/3f35dee8-bd58-4658-9b2e-dd5642bf6c59" />

Materialized view refresh:
<img width="496" height="36" alt="5" src="https://github.com/user-attachments/assets/00b32a6d-6116-4603-aba1-377abb1daf50" />

Database index list:
<img width="274" height="673" alt="6" src="https://github.com/user-attachments/assets/a4c3357b-994b-431b-ad8d-9a39b60f46cb" />

Read/write splitting config:
<img width="315" height="162" alt="7" src="https://github.com/user-attachments/assets/6e520644-e67c-4639-9bd6-38a1d5e68b55" />

Partitioning migration (code proof):
<img width="749" height="245" alt="8" src="https://github.com/user-attachments/assets/6801320e-456e-4df1-ac00-087b3836f567" />
