"use strict";
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
/**
 * {{{model_name}}} Resolve Single
 * @copyright 2020 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
module.exports = (parent, args, context, info) => {
  //Check Auth if user allowed
  //Join table here if need?
  // console.log(context.db.{{{model_name}}}.getMapping());
  //return context.db.{{{model_name}}}.findByPk(args.id, {
  //  include: [{ model: context.db.other_table, required: false }]
  //});
  return context.db.{{{model_name}}}.findByPk(args.id);
}
