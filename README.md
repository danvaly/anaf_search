# API ANAF
Librarie PHP pentru verificarea gratuita a contribuabililor care sunt inregistrati conform art. 316 din Codul Fiscal

Date care pot fi obtinute:
  - Denumire/Adresa companie
  - Numar Registrul Comertului
  - Numar de telefon
  - Platitor/Neplatitor TVA
  - Platitor TVA la incasare
  - Platitor Split TVA pana la 1 februarie 2020 (**OUG 23/2017 privind plata defalcată a TVA a fost abrogata incepand cu 1 februarie 2020**)
  - IBAN Split TVA
  - Data inregistrare TVA
  - Status Societate (Activa/Inactiva)
  - Data radiere
   
# Instalare

```shell
composer require danvaly/anaf_search
```

# Exemplu de folosire

- Initializare librarie

```php
$anaf = new \Danvaly\AnafSearch\Anaf(); 
```

### Pentru a verifica doar un CUI foloseste metoda 

```php
$cif = "123456";
$dataVerificare = "YYYY-MM-DD";
$anaf->addCif($cif, $dataVerificare);
```


#### Conform exemplului de mai jos:

```php
$cif = "123456";
$dataVerificare = "2019-05-20";
$anaf->addCif($cif, $dataVerificare);
$company = $anaf->first();

// Metode disponibile
echo $company->getName();
echo $company->getCIF();
echo $company->getRegCom();
echo $company->getPhone();

echo $company->getFullAddress();
echo $company->getAddress()->getCity();
echo $company->getAddress()->getCounty();
echo $company->getAddress()->getStreet();
echo $company->getAddress()->getStreetNumber();
echo $company->getAddress()->getPostalCode();
echo $company->getAddress()->getOthers();

echo $company->getTVA()->hasTVA();
echo $company->getTVA()->getTVAEnrollDate();
echo $company->getTVA()->getTVAEndDate();

echo $company->getTVA()->hasTVACollection();
echo $company->getTVA()->getTVACollectionEnrollDate();
echo $company->getTVA()->getTVACollectionEndDate();

echo $company->getTVA()->hasTVASplit();
echo $company->getTVA()->getTVASplitEnrollDate();
echo $company->getTVA()->getTVASplitEndDate();
echo $company->getTVA()->getTVASplitIBAN();

echo $company->getReactivationDate();
echo $company->getInactivationDate();
echo $company->getDeletionDate();
echo $company->isActive();

sau 

echo $company->name;
echo $company->cif;
echo $company->reg_com;
echo $company->phone;
echo $company->full_address;
echo $company->city;
echo $company->county;
echo $company->street;
echo $company->street_number;
echo $company->postal_code;
echo $company->others;

echo $company->has_tva;
echo $company->tva_enroll_date;
echo $company->tva_end_date;

echo $company->has_tva_collection;
echo $company->tva_collection_enroll_date;
echo $company->tva_collection_end_date;

echo $company->has_tva_split;
echo $company->tva_split_enroll_date;
echo $company->tva_split_end_date;
echo $company->tva_split_iban;

echo $company->reactivation_date;
echo $company->inactivation_date;
echo $company->deletion_date;
echo $company->is_active;

sau 

$anaf->toArray(); // Returneaza un array cu toate datele
$anaf->toJson(); // Returneaza un string JSON cu toate datele
```

### Pentru a verifica mai multe CUI-uri in acelasi timp foloseste urmeaza exemplul de mai jos:

```php
$anaf->addCif("123456", "2019-05-20");
$anaf->addCif("RO654321"); // Daca data nu este setata, valoarea default va fi data de azi
$raspuns = $anaf->get();

// SAU

$cifs = [
  "123456",
  "RO6543221"
];
$anaf->addCif($cifs, "2019-05-20");
$raspuns = $anaf->get();
```

# Limite
Poti solicita raspuns pentru maxim 500 de CUI-uri simultan cu o rata de 1 request / secunda. 

# Requirements
* PHP >= 8.2
* Laravel >= 10.0
* Ext-Curl
* Ext-Json
* Ext-Mbstring

# Exceptii:

* Danvaly\AnafSearch\Exceptions\LimitExceeded - Ai depasit limita de 500 de CUI-uri / request;
* Danvaly\AnafSearch\Exceptions\ResponseFailed - Raspunsul primit de la ANAF nu este in format JSON, exceptia returneaza body-ul raspunsului pentru a fi verificat manual;
* Danvaly\AnafSearch\Exceptions\RequestFailed - Raspunsul primit de la ANAF nu are status de succes, verifica manual raspunsul primit in exceptie.

# Upgrade de la 2 la 3
Versiunea 2 nu este compatibila cu versiunea 3, daca aveti o implementare vechie, trebuie refacuta pentru a fi compatibila.

# Linkuri utile
https://webservicesp.anaf.ro/PlatitorTvaRest/api/v6/
