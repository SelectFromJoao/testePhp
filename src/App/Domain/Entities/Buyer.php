<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Buyer
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $email;

 
    /**
     * @return string
     */   
    public function getId(): string
    {
        return $this->id;
    }

     /**
     * @param string $id
     */   
    public function setId(string $id)
    {
        $this->id = $id;
    }

     /**
     * @return string
     */      
    public function getName(): string
    {
        return $this->name;
    }

     /**
     * @param string $name
     */       
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */       
    public function getEmail(): string
    {
        return $this->email;
    }

     /**
     * @param string $email
     */       
    public function setEmail(string $email)
    {
        $this->email = $email;
    }    
}
