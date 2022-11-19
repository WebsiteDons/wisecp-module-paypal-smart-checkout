# Using the XML markup form system to add fields
This is a method which allows a simplified markup method to create repetitive HTML formatting for form fields. 

**Example**

Consider the action of adding the following HTML per form field. It gets quite tedious and lead to clutter within the module's `pages/settings.php` template file. With this process, there is no need to ever edit the file to add fields.

```html
<div class="control-wrap">
  <div class="label"><label>Field Label</label></div>
  <div class="control">
    <input type="text" name="data" id="data" value="" placeholder="hint of what to enter" />
  </div>
</div>
```

**Now with the XML markup** file `form/form.xml`

Multiple fields added with various attributes

**note** you only add within the `<fieldset>` tags `<field type="" name="" label="" />`. Below is just a complete visual of the file XML format.

```xml
<?xml version="1.0" encoding="utf-8"?>
<form>
  <fieldset>
    <field type="text" name="data" hint="what to enter" note="enter only alpha characters" label="Field Label" />
    <field type="textarea" name="message" fclass="wide-fat" label="The Message" />
    <field 
           type="list" 
           name="selector" 
           options="{'juice':'Juice','wine':'Wine','water':'Water'}" 
           default="water" 
           data_attrib="jshandler" 
           label="Drink choice" 
           />
    <field type="title" note="Enter user personal data below" label="User Data Fields" />
  </fieldset>
</form>
```
The HTML output is automated by `class.form.php`. Each field name will be detected on `$_POST` and value written to `config.php`

### Output a field value in checkout view
Edit `pages/payform.php` and place the HTML above or below the PHP container for `echo $module->smartCheckout();` 
```html
<p><?php echo (isset($module->setting->message) ? $module->setting->message:''); ?></p>
```
