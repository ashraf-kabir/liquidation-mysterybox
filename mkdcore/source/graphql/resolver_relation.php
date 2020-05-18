"use strict";
module.exports = (parent, args, context, info) => {
  if (parent.{{{model_name}}}) {
    return parent.{{{model_name}}};
  } else {
    return parent.get{{{upper_model_name}}}();
  }
};
