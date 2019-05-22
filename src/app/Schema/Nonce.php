<?php
namespace UsingRefs;
?>
A common scenario is to let swagger-php generate a definition based on your model class.
These definitions can then be referenced with `ref="#/components/schemas/$classname"
<?php
/**
 * @OA\Schema(
 *     description="Product model",
 *     type="object",
 *     title="Product model"
 * )
 */
class Standard
{
    /**
     * The unique identifier of a product in our catalog.
     *
     * @var integer
     * @OA\Property(format="int64", example=1)
     */
    public $ret;
    /**
     * @OA\Property(ref="#/components/schemas/product_status")
     */
    public $data;
    /**
     * The unique identifier of a product in our catalog.
     *
     * @var string
     * @OA\Property(format="int64", example=1)
     */
    public $msg;
}
