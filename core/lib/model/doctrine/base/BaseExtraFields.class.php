<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('ExtraFields', 'doctrine');

/**
 * BaseExtraFields
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $bind_type
 * @property string $type
 * @property integer $sort_order
 * @property integer $active
 * @property integer $display_in_list
 * @property Doctrine_Collection $ExtraFieldsList
 * 
 * @method integer             getId()              Returns the current record's "id" value
 * @method string              getName()            Returns the current record's "name" value
 * @method string              getBindType()        Returns the current record's "bind_type" value
 * @method string              getType()            Returns the current record's "type" value
 * @method integer             getSortOrder()       Returns the current record's "sort_order" value
 * @method integer             getActive()          Returns the current record's "active" value
 * @method integer             getDisplayInList()   Returns the current record's "display_in_list" value
 * @method Doctrine_Collection getExtraFieldsList() Returns the current record's "ExtraFieldsList" collection
 * @method ExtraFields         setId()              Sets the current record's "id" value
 * @method ExtraFields         setName()            Sets the current record's "name" value
 * @method ExtraFields         setBindType()        Sets the current record's "bind_type" value
 * @method ExtraFields         setType()            Sets the current record's "type" value
 * @method ExtraFields         setSortOrder()       Sets the current record's "sort_order" value
 * @method ExtraFields         setActive()          Sets the current record's "active" value
 * @method ExtraFields         setDisplayInList()   Sets the current record's "display_in_list" value
 * @method ExtraFields         setExtraFieldsList() Sets the current record's "ExtraFieldsList" collection
 * 
 * @package    sf_sandbox
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseExtraFields extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('extra_fields');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('bind_type', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('type', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('sort_order', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('active', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 1,
             ));
        $this->hasColumn('display_in_list', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('ExtraFieldsList', array(
             'local' => 'id',
             'foreign' => 'extra_fields_id'));
    }
}