<?php return ['routes'=>[
	'Manual/Controllers/Manual::index'=>'/manual',
	'Sensorario/Controllers/Sensorario::contatti'=>'/contatti/contacts',
	'Sensorario/Controllers/Altro::index'=>'/',
	'Blog/Controllers/Dashboard::sandro'=>'/blog/dashboard',
],'actions'=>[
	'/manual'=>'Manual/Controllers/Manual::index',
	'/contatti/contacts'=>'Sensorario/Controllers/Sensorario::contatti',
	'/'=>'Sensorario/Controllers/Altro::index',
	'/blog/dashboard'=>'Blog/Controllers/Dashboard::sandro',
],'views'=>[
	'Manual/Controllers/Manual::index'=>'index',
	'Sensorario/Controllers/Sensorario::contatti'=>'contatti',
	'Sensorario/Controllers/Altro::index'=>'index',
	'Blog/Controllers/Dashboard::sandro'=>'dashboard',
],'names'=>[
	'manual_homepage'=>'Manual/Controllers/Manual::index',
	'contatti'=>'Sensorario/Controllers/Sensorario::contatti',
	'homepage'=>'Sensorario/Controllers/Altro::index',
	'blog_dashboard'=>'Blog/Controllers/Dashboard::sandro',
],'layouts'=>[
	'Manual/Controllers/Manual::index'=>'default',
	'Sensorario/Controllers/Sensorario::contatti'=>'default',
	'Sensorario/Controllers/Altro::index'=>'default',
	'Blog/Controllers/Dashboard::sandro'=>'default',
]];