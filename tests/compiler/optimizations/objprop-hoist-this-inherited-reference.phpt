--TEST--
Hoisted this numeric slots preserve inherited properties and existing references
--FILE--
<?php
class SlotBase {
    public int $count = 2;
    public float $weight = 1.5;
}
class SlotChild extends SlotBase {
    public function update(): void {
        $this->count += 3;
        $this->weight += 0.5;
    }
}
function main(): void {
    $object = new SlotChild();
    $count =& $object->count;
    $weight =& $object->weight;
    $object->update();
    var_dump($count, $weight);
    $count = 10;
    $weight = 3.5;
    $object->update();
    var_dump($count, $weight);
}
?>
--EXPECT--
int(5)
float(2)
int(13)
float(4)
