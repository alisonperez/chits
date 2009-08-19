<table width="300"><tr><td>
<h3>THE G.A.M.E. Engine</h3>
<p>GAME stands for Generic Architecture for Modular Enterprise.</p>
<p>The GAME Engine for software development is based on the use of<br/>
Entity-Relationship Diagrams for module development. Entity Relationship <br/>
Diagrams or <b>ERDs</b> can be representations of the health care context <br/>
in which software development activities are carried out. For example, <br/>
when creating a patient consult module, the developer will need to<br/>
translate the validated data model into an actual module with library tables <br/>
as dependency modules. With a little creativity and imagination, the developer<br/>
can extrapolate the applicability of this platform to almost any situation,<br/>
including creating modules within modules.</p>
<p>The development platform forces the developer to focus on one module at a time,<br />
and think through the implementation of few and specific database model entities <br />
at a time, concentrate on making the code modular and create interfaces to other <br />
either through the standard internal module class (below) or through modules. The<br />
key to enabling interactivity between modules is by looking at bridge entities.</p>

<h3>MODULE DEVELOPMENT HOWTO</h3>
<p>
This modular system has a two-tier architecture:
<ol>
<li>System level - takes care of module installation, table creation and cleanup,<br/>
module dependency resolution and provides module methods accessible to external <br/>
modules. Please see module system source code below.</li>
<li>Module - level - takes care of end-user side application requirements. </li>
</ol>
<p>For forms processing, the general steps used are:
<ol>
<li>Scripts are executed as follows: <b>form_&lt;name&gt;</b> -> <b>process_&lt;name&gt;</b> -> <b>display_&lt;name&gt;</b></li>
<li>A form is displayed, then the end-user fills it up and submits it. Form and <br />
URL variables are passed to the processing script for validation and database entry.</li>
<li>Database tables are displayed.</li>
</ol>
The developer will find this extensively used throughout the internal and external modules.
</p>

Please browse through any of the available module's source code <a href="index.php?page=MODULES&method=MODDB">here</a>.
</p>
<h3>MODULE DATA MODEL</h3>
<p>This is the first-tier data model in the GAME platform. Second-tier data models<br />
are the content (data) of the module data model. What the first-tier data model <br />
contains, in short, is meta-data about the loaded modules and how they relate to one <br />
another.</p>
<img src="../images/game_model.png" border="0"/>

<h3>MODULE CLASS SOURCE CODE</h3>
<small>
<?
show_source("../class.module.php");
?>
</small>
</td></tr></table>
