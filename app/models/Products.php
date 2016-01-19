<?php



use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Products extends Model
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
     * @var integer
     */
    protected $category_id;

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
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param integer $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('category_id', 'Products\App\Models\Categories', 'id', ['alias' => 'Categories']);
    }

    public function validation()
    {

        $this->validate(
            new Uniqueness(
                array(
                    "field"   => "name",
                    "message" => "The product name must be unique"
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