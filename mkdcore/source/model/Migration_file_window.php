<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * {{{upper_case_model}}} Migration
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Migration_{{{model}}} extends CI_Migration {

        public function up()
        {
          $this->dbforge->add_field({{{migration}}});
          $this->dbforge->add_key('id', TRUE);
          $this->dbforge->create_table('{{{model}}}');
          {{{after}}}
        }

        public function down()
        {
                $this->dbforge->drop_table('{{{model}}}');
        }
{{{after_function}}}
}