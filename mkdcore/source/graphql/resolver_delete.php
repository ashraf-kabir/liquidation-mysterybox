"use strict";
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
/**
 * {{{model_name}}} Resolve Delete
 * @copyright 2020 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
module.exports = (parent, args, context, info) => {
  //Check Auth if user allowed
  return context.db.{{{model_name}}}.destroy({
    where: {
      id: args.id
    }
  });
}