<?php



use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Categories extends Model
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;


    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Products\App\Models\Products', 'category_id', ['alias' => 'Products']);
    }

    public function validation()
    {

        $this->validate(
            new Uniqueness(
                array(
                    "field"   => "name",
                    "message" => "The category name must be unique"
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public function setAttributes($attributes)
    {
        foreach ($attributes as $k => $v) {
            $this->$k = $v;
        }
    }

}