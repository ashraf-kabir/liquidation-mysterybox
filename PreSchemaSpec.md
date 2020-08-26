# PreSchema
The idea of this is have a simpler file where a non tech person can write the doc and run a script and generate all fields to have fully functional system. Then the config is pass to developer to remove unneeded fields and create custom pages.

The way I was thinking of making this is the following rules:
1.Every line must end with ; So you can do explode(';', text) to get each command
2.first word before ~ is the command to translate.
3.All fields after ~ can be seperated by |


## Commands

power_by - power_by in config

roles - seperate by |, write into roles array in config

core - write core model role,setting,image,refer_log,user,token,email,sms,<role>_operation(make 1 for each role), core controllers for admin portal only, core translation in current config we have

model - seperate by |, type of field is seperate by :. Translate field into model. Also add xyz<field_name> to label and translation object. Default everything else like migration, timestamp

```
model~black_list_token|token:STR|status:INT|issue_at:DATE|expire_at:DATETIME|data:TEXT|image:IMAGE|image_id:INT|password:PASSWORD|file:FILE|money:FLOAT;
```

mapping - seperate by |, first field is model table name, everything after is mapping field

```
mapping~black_list_token|status|active|inactive|suspend|ban;
```

portal - seperate by |, first field is role, second field is model, third is login type. Preset rest

```
portal~admin|user_model|login_only;
```

menu - seperate by |, first field is portal, second field label(remember add xyz here), third is route
- also sub menu, where second field has ( and label and route seperate by ::. comma seperate the different links

```
menu~admin|Dashboard|/dashboard;

menu~admin|Report(User Report::/report/users,Report 1/reports/2);
```

controller - seperate by |, first model, second page name(add to translation), third is route, fourth is portal, everything else set to default

filter - seperate by |, first model, second portal, field seperate by comma

list - seperate by |, first model, second portal, field seperate by comma

view - seperate by |, first model, second portal, field seperate by comma

header - seperate by |, first model, second portal, field seperate by comma(add to xyz)

add - seperate by |, first model, second portal, field seperate by comma

edit - seperate by |, first model, second portal, field seperate by comma

listing_row - seperate by |, first model, second portal, field seperate by comma. All | are replace by = sign

```
controller~user|xyzUsers|/users|member;
filter~user|admin|id,email,first_name,last_name,role_id,status;
header~user|admin|id,email,first_name,last_name,role_id,status;
edit~user|admin|id,email,first_name,last_name,role_id,status;
add~user|admin|email,first_name,last_name,role_id;
row~user|admin|id=integer,image=image,complex=email:first_name:last_name:role_id:phone,status=integer;
```

package - if in list add as true

```
package~analytics|payment|cache|pdf|voice
```

simple_controller helps us type way less. All it does is it mark the portal(index 1), then everything after are models it will generate.

We will generate all fields into every section and you will have to remove field you dont want yourself.

Reason we made this was no one using controller tag above.
```
simple_controller~admin|history|occupation;
```