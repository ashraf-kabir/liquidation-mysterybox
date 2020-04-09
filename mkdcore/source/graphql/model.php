/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
/**
 * {{{uppercase_model}}} Model
 * @copyright 2020 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
module.exports = (sequelize, DataTypes) => {
  const {{{uppercase_model}}} = sequelize.define(
    "{{{model}}}",
    {
      id: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
      },
{{{fields}}}
    },
    {
      timestamps: false,
      freezeTableName: true,
      tableName: "{{{model}}}"
    },
    {
      underscoredAll: true,
      underscored: true
    }
  );
{{{associations}}}
{{{mapping}}}
  return {{{uppercase_model}}};
};
