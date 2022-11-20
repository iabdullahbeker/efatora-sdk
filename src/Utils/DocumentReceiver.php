<?php

namespace Iabdullahbeker\EfatoraSdk\Utils;


class DocumentReceiver
{

    private $country;
    private $governate;
    private $regionCity;
    private $street;
    private $buildingNumber;
    private $postalCode;
    private $floor;
    private $room;
    private $landmark;
    private $additionalInformation;


    private $receiver_type;
    private $receiver_id;
    private $receiver_name;

    public function __construct(array $args)
    {
        $this->country = $args['country'];
        $this->governate = $args['governate'];
        $this->regionCity = $args['regionCity'];
        $this->street = $args['street'];
        $this->buildingNumber = $args['buildingNumber'];
        $this->postalCode = $args['postalCode'];
        $this->floor = $args['floor'];
        $this->room = $args['room'];
        $this->landmark = $args['landmark'];
        $this->additionalInformation = $args['additionalInformation'];

        $this->receiver_type = $args['receiver_type'];
        $this->receiver_id = $args['receiver_id'];
        $this->receiver_name = $args['receiver_name'];
    }

    public function toArray(): array
    {
        return [
            "address" => [
                "country" => $this->country,
                "governate" => $this->governate,
                "regionCity" => $this->regionCity,
                "street" => $this->street,
                "buildingNumber" => $this->buildingNumber,
                "postalCode" => $this->postalCode,
                "floor" => $this->floor,
                "room" => $this->room,
                "landmark" => $this->landmark,
                "additionalInformation" => $this->additionalInformation
            ],
            "type" => $this->receiver_type,
            "id" => $this->receiver_id,
            "name" => $this->receiver_name
        ];
    }
}
