create database istc;
	use symetri;
	set sql_mode='';

	create table  ubdepartamento(
		id int primary key not null auto_increment,
		departamento varchar(250)
	);

	create table  ubprovincia(
		id int primary key not null auto_increment,
		provincia varchar(250),
		ubdepartamento int,
		foreign key (ubdepartamento) references ubdepartamento(id)
	);

	create table  ubdistrito(
		id int primary key not null auto_increment,
		distrito varchar(250),
		ubprovincia int,
		foreign key (ubprovincia) references ubprovincia(id)
	);

	create table  estudiante(
		id int primary key not null auto_increment,
		ubdistrito int,
		dni_est char(8),
		ap_est varchar(40),
		am_est varchar(40),
		nom_est varchar(40),
		sex_est char(1),
		cel_est  char(9),
		ubigeodir_est char(6),
		ubigeonac_est char(6),
		dir_est varchar(40),
		mailp_est varchar(40),
		maili_est varchar(40),
		fecnac_est date,
		foto_est varchar(40),
		estado int,
		foreign key (ubdistrito) references ubdistrito(id)
	);

	create table  prog_estudios(
		id int primary key not null auto_increment,
		nom_progest varchar(40),
		perfilingre_progest text,
		perfilegre_progest text
	);

	create table  empleado(
		id int primary key not null auto_increment,
		prog_estudios int,
		dni_emp char(8),
		apnom_emp varchar(60),
		sex_emp char(1),
		cel_emp char(9),
		ubigeodir_emp  char(6),
		ubigeonac_emp char(6),
		dir_emp varchar(40),
		mailp_emp varchar(40),
		maili_emp varchar(40) ,
		fecnac_emp date,
		cargo_emp char(1),
		cond_emp char(1),
		id_progest char(3),
		fecinc_emp date,
		foto_emp varchar(40),
		estado int,
		foreign key (prog_estudios) references prog_estudios(id)
	);

	create table  matricula(
		id int primary key not null auto_increment,
		estudiante int,
		prog_estudios int,
		id_matricula char(9),
		per_lectivo varchar(7),
		per_acad varchar(3),
		per_acad2 int(1),
		seccion char(1),
		turno char(1),
		fec_matricula date,
		cond_matricula char(1),
		est_matricula char(1),
		est_perlec char(1),
		obs_matricula varchar(50),
		foreign key (estudiante) references estudiante(id),
		foreign key (prog_estudios) references prog_estudios(id)
	);

	create table  usuarios(
		id int primary key not null auto_increment,
		usuario varchar(200),
		password text,
		tipo int, -- 1 ES EMPLEADO, 2 ES ESTUDIANTE, 3 ES EMPRESA  
		estuempleado int,
		token text
	);

	/* SISTEMA DE CONVENIOS */

	create table instituto(
		id int primary key not null auto_increment,
		ruc varchar(11),
		razon_social varchar(255),
		nombre_comercial varchar(255),
		direccion text,
		telefono varchar(20),
		email varchar(100),
		logo varchar(500),
		activa int
	);

	create table empresa(
		id int primary key not null auto_increment,
		ruc varchar(11),
		razon_social varchar(255),
		nombre_comercial varchar(255),
		direccion_fiscal text,
		telefono varchar(20),
		email varchar(100),
		sector varchar(100),
		validado int,
		registro_manual int,
		estado varchar(50),
		condicion_sunat varchar(20),
		ubigeo varchar(10),
		departamento varchar(100),
		provincia varchar(100),
		distrito varchar(100),
		fecha_creacion datetime,
		fecha_actualizacion datetime
	);

	create table cache_sunat(
		id int primary key not null auto_increment,
		ruc varchar(11),
		razon_social varchar(255),
		nombre_comercial varchar(255),
		direccion_fiscal text,
		departamento varchar(100),
		provincia varchar(100),
		distrito varchar(100),
		ubigeo varchar(6),
		estado varchar(50),
		condicion varchar(50),
		representante_legal varchar(255),
		representante_cargo varchar(150),
		representante_desde date,
		telefono varchar(20),
		fecha_inscripcion date,
		fecha_inicio_actividades date,
		fecha_consulta datetime,
		fecha_expiracion datetime
	);

	create table representante(
		id int primary key not null auto_increment,
		instituto int,
		empresa int,
		nombre_completo varchar(255),
		cargo varchar(100),
		documento varchar(20),
		tipo_documento varchar(20),
		email varchar(100),
		telefono varchar(20),
		puede_firmar int,
		fecha_desde date,
		fecha_hasta date,
		activo int,
		foreign key (instituto) references instituto(id),
		foreign key (empresa) references empresa(id)
	);

	create table tipo_convenio(
		id int primary key not null auto_increment,
		nombre varchar(100),
		descripcion text,
		plantilla varchar(500),
		requiere_coord_academica int,
		activa int
	);

	create table estado_convenio(
		id int primary key not null auto_increment,
		nombre varchar(100),
		descripcion text,
		color varchar(20),
		orden int
	);

	create table convenio(
		id int primary key not null auto_increment,
		instituto int,
		empresa int,
		tipo int,
		estado int,
		codigo varchar(50),
		iniciado_por varchar(20),
		objetivo text,
		beneficios_instituto text,
		beneficios_empresa text,
		duracion_meses int,
		fecha_inicio date,
		fecha_vencimiento date,
		renovacion_automatica int,
		condiciones_especiales text,
		archivo_fisico varchar(255),
		qr varchar(500),
		fecha_creacion datetime,
		fecha_activacion datetime,
		foreign key (instituto) references instituto(id),
		foreign key (empresa) references empresa(id),
		foreign key (tipo) references tipo_convenio(id),
		foreign key (estado) references estado_convenio(id)
	);

	create table comunicacion(
		id int primary key not null auto_increment,
		convenio int,
		tipo varchar(50),
		asunto varchar(255),
		contenido text,
		enviado_por int,
		fecha_envio datetime,
		fecha_respuesta datetime,
		respondido int,
		archivo varchar(500),
		tipouser int, /* 1 es institución, 2 es empresa */
		foreign key (convenio) references convenio(id)
	);

	create table reunion(
		id int primary key not null auto_increment,
		convenio int,
		fecha datetime,
		duracion int,
		modalidad varchar(20),
		link_virtual varchar(500),
		lugar varchar(255),
		estado varchar(20),
		acta varchar(500),
		propuesta_por int,
		fecha_propuesta datetime,
		fecha_confirmacion datetime,
		tipouser int, /* 1 es institución, 2 es empresa */
		foreign key (convenio) references convenio(id)
	);

	create table participante_reunion(
		id int primary key not null auto_increment,
		reunion int,
		usuario int,
		rol varchar(20),
		confirmo int,
		asistio int,
		tipouser int, /* 1 es institución, 2 es empresa */
		foreign key (reunion) references reunion(id)
	);

	create table invitado_reunion(
		id int primary key not null auto_increment,
		reunion int,
		nombre_completo varchar(255),
		cargo varchar(100),
		entidad varchar(255),
		email varchar(100),
		telefono varchar(20),
		asistio int,
		foreign key (reunion) references reunion(id)
	);

	create table tipo_documento(
		id int primary key not null auto_increment,
		nombre varchar(100),
		descripcion text,
		plantilla varchar(500),
		requiere_firma int
	);

	create table documento(
		id int primary key not null auto_increment,
		convenio int,
		reunion int,
		tipo int,
		nombre varchar(255),
		descripcion text,
		archivo varchar(500),
		version int,
		tamano bigint,
		hash varchar(64),
		firmado_instituto int,
		firmado_empresa int,
		foreign key (convenio) references convenio(id),
		foreign key (reunion) references reunion(id),
		foreign key (tipo) references tipo_documento(id)
	);

	create table firma(
		id int primary key not null auto_increment,
		documento int,
		usuario int,
		representante int,
		tipo varchar(20),
		fecha datetime,
		ip varchar(45),
		metodo varchar(30),
		hash_documento varchar(64),
		observaciones text,
		tipouser int, /* 1 es institución, 2 es empresa */
		foreign key (documento) references documento(id),
		foreign key (representante) references representante(id)
	);

	create table nivel_alerta(
		id int primary key not null auto_increment,
		nombre varchar(20),
		descripcion text,
		color varchar(10),
		bloquea int,
		prioridad int
	);

	create table alerta_ia(
		id int primary key not null auto_increment,
		documento int,
		convenio int,
		nivel int,
		titulo varchar(255),
		descripcion text,
		clausula text,
		sugerencia text,
		fecha datetime,
		resuelta int,
		resuelto_por int,
		tipouser int, /* 1 es institución, 2 es empresa */
		foreign key (documento) references documento(id),
		foreign key (convenio) references convenio(id),
		foreign key (nivel) references nivel_alerta(id)
	);

	create table tipo_renovacion(
		id int primary key not null auto_increment,
		nombre varchar(50),
		descripcion text,
		requiere_firma int,
		duracion_dias int
	);

	create table estado_renovacion(
		id int primary key not null auto_increment,
		nombre varchar(50),
		descripcion text
	);

	create table renovacion(
		id int primary key not null auto_increment,
		convenio int,
		tipo int,
		estado int,
		iniciado_por varchar(20),
		fecha_inicio datetime,
		fecha_aprobacion datetime,
		meses_extension int,
		requiere_reunion int,
		observaciones text,
		foreign key (convenio) references convenio(id),
		foreign key (tipo) references tipo_renovacion(id),
		foreign key (estado) references estado_renovacion(id)
	);

	create table notificacion(
		id int primary key not null auto_increment,
		usuario int,
		convenio int,
		comunicacion int,
		tipo varchar(20),
		prioridad varchar(20),
		titulo varchar(255),
		mensaje text,
		leida int,
		fecha_envio datetime,
		email_enviado int,
		tipouser int, /* 1 es institución, 2 es empresa */
		foreign key (convenio) references convenio(id),
		foreign key (comunicacion) references comunicacion(id)
	);

	create table tipo_recordatorio(
		id int primary key not null auto_increment,
		nombre varchar(50),
		descripcion text
	);

	create table recordatorio(
		id int primary key not null auto_increment,
		notificacion int,
		tipo int,
		evento varchar(50),
		dias_antes int,
		fecha_programada datetime,
		enviado int,
		foreign key (notificacion) references notificacion(id),
		foreign key (tipo) references tipo_recordatorio(id)
	);

	create table auditoria(
		id int primary key not null auto_increment,
		usuario int,
		convenio int,
		accion varchar(20),
		tabla varchar(100),
		registro_id int,
		datos_anteriores text,
		datos_nuevos text,
		ip varchar(45),
		user_agent varchar(255),
		fecha datetime,
		descripcion text,
		tipouser int, /* 1 es institución, 2 es empresa */
		foreign key (convenio) references convenio(id)
	);

/* SISTEMA DE SEGUIMIENTOS DE EGRESADO   */

create table condicion_academica(
	id int primary key not null auto_increment,
	nombre_condicion varchar(50)
);

create table condicion_laboral(
	id int primary key not null auto_increment,
	nombre_condicion varchar(100)
);

create table sector(
	id int primary key not null auto_increment,
	nombre_sector varchar(100)
);

create table medio_consecucion(
	id int primary key not null auto_increment,
	nombre_medio varchar(100)
);

create table situacion_laboral(
	id int primary key not null auto_increment,
	estudiante int,
	empresa int,
	trabaja int,
	labora_programa_estudios int,
	cargo_actual varchar(200),
	condicion_laboral int,
	ingreso_bruto_mensual decimal(10,2),
	satisfaccion_trabajo varchar(50),
	fecha_inicio date,
	foreign key (estudiante) references estudiante(id),
	foreign key (empresa) references empresa(id),
	foreign key (condicion_laboral) references condicion_laboral(id)
);

create table historial_laboral(
	id int primary key not null auto_increment,
	situacion int,
	tiempo_primer_empleo varchar(30),
	razon_desempleo varchar(30),
	fecha_inicio date,
	fecha_fin date,
	fecha_registro datetime,
	foreign key (situacion) references situacion_laboral(id)
);

create table situacion_medio(
	situacion int,
	medio int,
	primary key (situacion, medio),
	foreign key (situacion) references situacion_laboral(id),
	foreign key (medio) references medio_consecucion(id)
);

create table tipo_seguimiento(
	id int primary key not null auto_increment,
	nombre_tipo varchar(100),
	descripcion varchar(200)
);

create table seguimiento(
	id int primary key not null auto_increment,
	estudiante int,
	tipo int,
	observaciones text,
	fecha date,
	foreign key (estudiante) references estudiante(id),
	foreign key (tipo) references tipo_seguimiento(id)
);

/*    SISTEMA DE EXPERIENCIAS FORMATIVAS  */

create table practicas(
	id int primary key not null auto_increment,
	estudiante int not null,
	empleado int,
	empresa int,
	modulo varchar(100),
	periodo_academico varchar(50),
	fecha_inicio date,
	fecha_fin date,
	total_horas int default 0,
	estado enum('En curso','Finalizado','Pendiente') default 'Pendiente',
	foreign key (estudiante) references estudiante(id) on delete cascade,
	foreign key (empleado) references empleado(id) on delete set null,
	foreign key (empresa) references empresa(id) on delete set null
);

create table asistencias(
	id int primary key not null auto_increment,
	practicas int not null,
	fecha date not null,
	hora_entrada time,
	hora_salida time,
	horas_acumuladas int,
	actividad text,
	visto_bueno_empresa varchar(150),
	visto_bueno_docente varchar(150),
	foreign key (practicas) references practicas(id) on delete cascade
);

create table evaluaciones(
	id int primary key not null auto_increment,
	practicas int not null,
	puntaje_total int,
	escala char(1),
	apreciacion enum('Muy Buena','Buena','Aceptable','Deficiente'),
	observaciones text,
	foreign key (practicas) references practicas(id) on delete cascade
);

create table evidencias(
	id int primary key not null auto_increment,
	practicas int not null,
	descripcion varchar(255),
	archivo_url varchar(255),
	fecha_subida timestamp default current_timestamp,
	foreign key (practicas) references practicas(id) on delete cascade
);

/** SISTEMA DE PAGOS Y DESCUENTOS */

create table resoluciones(
	id int primary key not null auto_increment,
	numero_resolucion varchar(50) not null unique,
	titulo varchar(255) not null,
	texto_respaldo text,
	ruta_documento varchar(255),
	fecha_inicio date,
	fecha_fin date,
	creado_por int,
	creado_en datetime,
	foreign key (creado_por) references empleado(id)
);

create table solicitudes(
	id int primary key not null auto_increment,
	estudiante int not null,
	resoluciones int,
	tipo_solicitud varchar(100) not null,
	descripcion text,
	estado enum('pendiente','en_evaluacion','aprobado','rechazado') default 'pendiente',
	fecha_solicitud datetime,
	fecha_revision datetime,
	empleado int,
	observaciones text,
	foto text,
	foreign key (estudiante) references estudiante(id),
	foreign key (resoluciones) references resoluciones(id),
	foreign key (empleado) references empleado(id)
);

create table historial_solicitudes(
	id int primary key not null auto_increment,
	solicitud_id int not null,
	estado varchar(50) not null,
	fecha datetime,
	empleado int,
	comentarios text,
	foreign key (solicitud_id) references solicitudes(id),
	foreign key (empleado) references empleado(id)
);

create table beneficiarios(
	id int primary key not null auto_increment,
	estudiante int not null,
	resoluciones int not null,
	porcentaje_descuento decimal(5,2) not null,
	fecha_inicio date,
	fecha_fin date,
	activo boolean default true,
	registrado_por int,
	registrado_en datetime,
	foreign key (estudiante) references estudiante(id),
	foreign key (resoluciones) references resoluciones(id),
	foreign key (registrado_por) references empleado(id)
);

create table historial_descuentos(
	id int primary key not null auto_increment,
	beneficiario_id int not null,
	monto_original decimal(10,2) not null,
	porcentaje_descuento decimal(5,2) not null,
	monto_descuento decimal(10,2) not null,
	monto_final decimal(10,2) not null,
	aplicado_por int,
	aplicado_en datetime,
	observaciones varchar(255),
	foreign key (beneficiario_id) references beneficiarios(id),
	foreign key (aplicado_por) references empleado(id)
);

create table tipo_pago(
	id int primary key not null auto_increment,
	nombre varchar(50) not null unique,
	descripcion varchar(150)
);

create table pagos(
	id int primary key not null auto_increment,
	estudiante int not null,
	solicitudes int,
	tipo_pago int not null,
	monto_original decimal(10,2) not null,
	monto_descuento decimal(10,2) default 0.00,
	monto_final decimal(10,2) not null,
	fecha_pago datetime,
	comprobante varchar(255),
	registrado_por int,
	registrado_en datetime,
	foreign key (estudiante) references estudiante(id),
	foreign key (solicitudes) references solicitudes(id),
	foreign key (tipo_pago) references tipo_pago(id),
	foreign key (registrado_por) references empleado(id)
);

/*     SISTEMA DE CAPACITACIÓN           */

create table tipo_curso (
    id int primary key auto_increment,
    nom_tipocurso varchar(50)
);

create table asistencia_cap (
    id int primary key auto_increment,
    asistencia boolean,
    certificacion boolean,
    certificado_archivo varchar(100)
);

create table curso (
    id int primary key auto_increment,
    nom_curso varchar(60) not null,
    modalidad_curso enum('presencial', 'virtual') not null,
    descripcion_curso text,
    fechini_curso date,
    fechfin_curso date,
    hora_curso varchar(50),
    lugar_curso varchar(100),
    estado_curso enum('vigente', 'finalizado') default 'vigente',
    organizador_curso varchar(100),
    certificacion_curso boolean default false,
    duracion_curso varchar(50),
    costo_curso decimal(5,2),
    creditos_curso char(3),
    foto_curso varchar(255),
    tipo_curso int,
    creado_por int,                         
    fecha_creacion datetime,
    fecha_modificacion datetime,
    foreign key (tipo_curso) references tipo_curso(id),
    foreign key (creado_por) references usuarios(id)
);

create table inscripcion (
    id int primary key auto_increment,
    noms_ins varchar(50),
    apaterno_ins varchar(50),
    amaterno_ins varchar(50),
    dni_ins char(8),
    telefono_ins char(9),
    correo_ins varchar(50),
    institucion_ins varchar(100),
    lugar_ins varchar(100),
    asistencia_cap int,
    curso int,
    tipo int,
    foreign key (asistencia_cap) references asistencia_cap(id),
    foreign key (curso) references curso(id)
);

create table pago_cap (
    id int primary key auto_increment,
    monto_pago decimal(5,2),
    fecha_pago date,
    estado_pago enum('porconfirmar', 'confirmado'),
    inscripcion int,
    foreign key (inscripcion) references inscripcion(id)
);

create table auditoria_cap (
    id int primary key auto_increment,
    tabla_afectada varchar(50),
    id_registro_afectado int,
    accion enum('insert', 'update', 'delete'),
    fecha_accion datetime,
    id_user int,
    descripcion text,
    tipo int,
    foreign key (id_user) references usuarios(id)
);

/** SISTEMA DE MATRICULA */

create table unidades_didacticas(
	id int auto_increment primary key not null,
	nombre_unidad varchar(100) not null,
	descripcion text,
	creditos int not null check (creditos > 0)
);