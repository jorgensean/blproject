<?php

class Leads extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(column="first_name", type="string", length=70, nullable=true)
     */
    public $first_name;

    /**
     *
     * @var string
     * @Column(column="last_name", type="string", length=70, nullable=true)
     */
    public $last_name;

    /**
     *
     * @var string
     * @Column(column="email_address", type="string", length=70, nullable=true)
     */
    public $email_address;

    /**
     *
     * @var string
     * @Column(column="address", type="string", length=255, nullable=true)
     */
    public $address;

    /**
     *
     * @var integer
     * @Column(column="square_footage", type="integer", length=10, nullable=true)
     */
    public $square_footage;

    /**
     *
     * @var string
     * @Column(column="created_on", type="string", nullable=false)
     */
    public $created_on;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("boldleadsdev");
        $this->setSource("leads");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'leads';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Leads[]|Leads|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Leads|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
