<?php

namespace Danvaly\AnafSearch\Models;

use Danvaly\AnafSearch\Parser;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Company implements Arrayable, Jsonable
{

    /** @var Parser */
    private $parser;

    public function __get(string $name)
    {
        $data = $this->toArray();
        if (isset($data[$name])) {
            return $data[$name];
        }
        return null;
    }

    /**
     * Company constructor.
     *
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return string
     */
    public function getCIF(): string
    {
        return $this->parser->getData()['cui'] ?? '';
    }

    /**
     * @return string
     */
    public function getRegCom(): string
    {
        return $this->parser->getData()['nrRegCom'] ?? '';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->parser->getData()['denumire'] ?? '';
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->parser->getData()['telefon'] ?? '';
    }

    /**
     * @return string
     */
    public function getFullAddress(): string
    {
        return $this->parser->getData()['adresa'] ?? '';
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        if (empty($this->parser->getData()['statusInactivi']) || !is_bool($this->parser->getData()['statusInactivi'])) {
            return false;
        }

        return !$this->parser->getData()['statusInactivi'];
    }

    /**
     * @return string
     */
    public function getInactivationDate(): string
    {
        return $this->parser->getData()['dataInactivare'] ?? '';
    }

    /**
     * @return string
     */
    public function getReactivationDate(): string
    {
        return $this->parser->getData()['dataReactivare'] ?? '';
    }

    /**
     * @return string
     */
    public function getDeletionDate(): string
    {
        return $this->parser->getData()['dataRadiere'] ?? '';
    }

    /**
     * @return CompanyTVA
     */
    public function getTVA(): CompanyTVA
    {
        return new CompanyTVA($this->parser);
    }

    /**
     * @return CompanyAddress
     */
    public function getAddress(): CompanyAddress
    {
        return new CompanyAddress($this->parser);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "name"    => $this->getName(),
            "cif"     => $this->getCIF(),
            "reg_com" => $this->getRegCom(),
            "phone"   => $this->getPhone(),

            "full_address"  => $this->getFullAddress(),
            "city"          => $this->getAddress()->getCity(),
            "country"       => $this->getAddress()->getCounty(),
            "street"        => $this->getAddress()->getStreet(),
            "street_number" => $this->getAddress()->getStreetNumber(),
            "postal_code"   => $this->getAddress()->getPostalCode(),
            "others"        => $this->getAddress()->getOthers(),

            "has_tva"         => $this->getTVA()->hasTVA(),
            "tva_enroll_date" => $this->getTVA()->getTVAEnrollDate(),
            "tva_end_date"    => $this->getTVA()->getTVAEndDate(),

            "tva_collection"             => $this->getTVA()->hasTVACollection(),
            "tva_collection_enroll_date" => $this->getTVA()->getTVACollectionEnrollDate(),
            "tva_collection_end_date"    => $this->getTVA()->getTVACollectionEndDate(),

            "tva_split"             => $this->getTVA()->hasTVASplit(),
            "tva_split_enroll_date" => $this->getTVA()->getTVASplitEnrollDate(),
            "tva_split_end_date"    => $this->getTVA()->getTVASplitEndDate(),
            "tva_split_iban"        => $this->getTVA()->getTVASplitIBAN(),

            "reactivation_date" => $this->getReactivationDate(),
            "inactivation_date" => $this->getInactivationDate(),
            "deletion_date"     => $this->getDeletionDate(),
            "is_active"         => $this->isActive(),
        ];
    }

    /**
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}

