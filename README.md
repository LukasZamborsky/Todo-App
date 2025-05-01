# ✅ ToDo-App (Laravel 12 + Vue 3 + Inertia.js)

ToDo aplikácia vytvorená ako výberové zadanie. Umožňuje spravovať úlohy, priraďovať tagy, filtrovať, vyhľadávať, označovať ako dokončené a ďalšie pokročilé funkcie. Backend je postavený na Laravel 12, frontend vo Vue 3 s použitím Inertia.js.

---

## 🧾 Splnené požiadavky zadania

### 🔹 Základné požiadavky ✅
- [x] Laravel 12 ako backend
- [x] MySQL databáza
- [x] CRUD operácie pre úlohy (vytvorenie, zobrazenie, úprava, vymazanie)
- [x] API endpointy pre správu úloh
- [x] Eloquent modely a migrácie
- [x] Laravel Routes a Controllers
- [x] Validácia pomocou Laravel Form Requests
- [x] Seedovanie databázy s dummy dátami
- [x] Unit testy (vytvorenie, úprava a zmazanie úlohy s rôznymi scenármi)

### 🔹 Špecifikácia funkcií ✅
- [x] Vytváranie novej úlohy s názvom a popisom
- [x] Označenie úlohy ako dokončenej
- [x] Úprava názvu a popisu úlohy
- [x] Vymazanie úlohy
- [x] Pridanie / zmazanie tagov k úlohám
- [x] Zobrazenie úloh podľa určitého tagu

---

## ⭐ Bonusové funkcie (nad rámec zadania)

- [x] Autentifikácia cez Laravel Sanctum (admin login s menom `admin`, heslo `admin`)
- [x] Frontend cez Inertia.js + Vue 3
- [x] Vyhľadávanie úloh podľa názvu alebo popisu
- [x] Filtrovanie: všetky úlohy, dnešné, dokončené
- [x] Stránkovanie úloh
- [x] Admin rozhranie s editáciou úloh
- [x] Pridávanie tagov cez svetlomodré UI
- [x] Vizuálne čistý dizajn a komponenty pre lepšiu UX/UI

---

## ⚙️ Inštalácia a spustenie

### 📦 Inštalácia závislostí
```bash
composer install
npm install
```

### 🔐 Nastavenie prostredia
```bash
cp .env.example .env
php artisan key:generate
```

### 🛠️ Konfigurácia databázy

V `.env` súbore nastav:

```
DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=todo_app  
DB_USERNAME=root  
DB_PASSWORD=
```

Spusti migrácie a seedovanie:
```bash
php artisan migrate --seed
```

### ▶️ Spustenie servera
```bash
php artisan serve
```

### 💻 Spustenie frontendu
```bash
npm run dev
```

### 🧪 Spustenie testov
```bash
php artisan test
```

---

## 👨‍💻 Autor

**Vypracoval:** Lukáš Žamborský  
**GitHub:** [@LukasZamborsky](https://github.com/LukasZamborsky)  
**Rok:** 2025
