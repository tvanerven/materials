# Materials browser API

Use Terms4FAIRskills ontology to browse materials.

## Installation

**Requirements:** PHP 7.4, MariaDB >= 10.3

**PHP Module required :**
- `php-cli`
- `php-json`
- `php-xml`
- `php-opcache`
- `php-mbstring`
- `php-intl`
- `php-process`
- `php-mysqlnd`
- `php-pdo`

#### 1. Download Symfony :  
` wget https://get.symfony.com/cli/installer -O - | bash `

#### 2. Export Symfony binaries into environment :  
`export PATH="$HOME/.symfony/bin:$PATH"`

#### 3. Download and install Composer :  
```
php composer-setup.php --install-dir=<intall-dir> --filename=<bin-name>
alias composer="<install-dir>/<bin-name>"
```

#### 4. Clone project
```
git clone https://dci-gitlab.cines.fr/dad/materials-browser-api.git
cd esgbu-api
```
#### 5. Download "vendor" with Composer :  
`composer install`

#### 6. Create ".env.local" file and add informations related from your setup :
```
# env.local data for local install

# MySQL Database
DATABASE_URL=mysql://<db_user>:<db_password>@127.0.0.1:3306/<db_name>?serverVersion=5.5

# Symfony env/debug parameters:
#APP_ENV=prod
#APP_DEBUG=0
```

#### 7. Database initialisation

Launch command to import T4FS ontology and materials into database from files `t4fs.owl` and `asset/materials.json` :
`bin/console app:import-ontology`

#### 8. Launch Symfony server :  
`bin/console cache:clear && symfony server:start`

#### 9. Display API into navigateur :
Go to address `http://localhost:8000/` from the internet browser. If the API documentation is displayed, the project is operational.

## Add materials and/or update ontology
To add materials into database or add new concepts or relation from ontology, replace files `materials.json` or/and `t4fs.owl` with newest into `asset` directory and relaunch command from **step 7** to rebuild database with new data.