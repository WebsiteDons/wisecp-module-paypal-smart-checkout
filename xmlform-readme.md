# Using the XML form system
This is method which allows a simplified markup method to create repetitive HTML formatting for form fields.

**Example**

Consider the action of adding the following HTML per form field. It gets quite tedious and lead to clutter within the `.html` template file

```html
<div class="controls">
  <div class="label"><label>Field Label</label></div>
  <div class="control">
    <input type="text" name="data" id="data" value="" placeholder="hint of what to enter" />
  </div>
</div>
```

**Now with the XML markup**

Multiple fields added with various attributes

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
The HTML output is handled by a PHP process.
