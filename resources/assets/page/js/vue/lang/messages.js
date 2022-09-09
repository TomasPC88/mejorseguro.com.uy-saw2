export const messages = {
	es: {
		validaciones: {
			custom: {
				nombre: {
					required: 'Por favor introduzca su nombre'
				},
				apellido: {
					required: 'Por favor introduzca su apellido'
				},
				direccion: {
					required: 'Por favor introduzca su dirección particular'
				},
				ciudad: {
					required: 'Por favor introduzca la ciudad en que se localiza'
				},
				departamento: {
					required: 'Por favor introduzca el departamento en donde radica'
				},
				codigo_postal: {
					required: 'Por favor introduzca su código postal'
				},
				envio_depto: {
					required: 'Por favor introduzca el departamento destinado a los envíos'
				},
				envio_ciudad: {
					required: 'Por favor introduzca la ciudad destinada a los envíos'
				},
				envio_dir: {
					required: 'Por favor introduzca la dirección destinada a los envíos'
				},
				envio_cod_postal: {
					required: 'Por favor introduzca el código postal localizado para los envíos'
				},
				email: {
					required: 'Por favor introduzca su correo eléctronico',
					email: 'La dirección de correo entrada no es válida'
				},
				cedula: {
					required: 'Por favor introduzca su cédula',
					regex: 'El formato es incorrecto, la sintaxis debe ser: #.###.###-#'
				},
				asunto: {
					required: 'Por favor introduzca el asunto de su mensaje',
				},
				consulta: {
					required: 'Por favor introduzca su consulta',
				},
				telefono: {
					required: 'Por favor introduzca su número de teléfono',
					numeric: 'El número teléfonico debe estar compuesto sólo por números'
				},
				celular: {
					required: 'Por favor introduzca su número de celular',
					regex: 'El formato es incorrecto, la sintaxis debe ser: 09# ### ###'
				},
				password: {
					required: 'Por favor introduzca una contraseña',
					min: 'Su contraseña debe tener un mínimo de 8 caracteres'
				},
				confirm: {
					required: 'Por favor introduzca una contraseña de confirmación',
					confirmed: 'Las contraseñas no coinciden'
				}
			}
		},
		labels: {
			garantia:'En caso que el conductor sea  menor de 23 años o el permiso de conducir tenga menos de dos años de antigüedad el importe de la garantía se incrementará un 50%'
		},
		errors:{
			no_minimo_dias_alquilar:'Mínimo de días para alquilar: {0}',
			restriccion:'Restricción',
			lugar:'El {0} seleccionado no está abierto el {1} {2}',
			lugar_fuera_horario:{
				entrega:'{0} no entrega fuera de horario de oficina, {1}:00 H',
				devolucion:'{0} no acepta devolucion fuera de horario de oficina {1}:00 H'
			},
			rango_fecha_incorrecto:'El rango de fechas seleccionado es incorrecto',
			min_dias_x_lugar:'Los días de alquiler deben superar los {0} días para devolver el vehículo a {1}'
		},
		form: {
			label: {
				//aside
				precio_x_dia: 'Precio por día',
				transmision: 'Transmisión',
				limpiar_filtros:'Limpiar Filtros',
				//checkboxes
				manual: 'Manual',
				automatica: 'Automática',
				//locations
				lugar_entrega: 'Lugar de Entrega',
				lugar_devolucion: 'Lugar de Devolución',
				fecha_entrega: 'Fecha de Entrega',
				fecha_devolucion: 'Fecha de Devolución',
				hora_entrega: 'Hora de Entrega',
				hora_devolucion: 'Hora de Devolución',
				//Subscription
				label_subscription: 'Suscribite y recibí información',
				//paginator
				anterior: 'Anterior',
				siguiente: 'Siguiente',
				title: 'Datos Personales',
				reservar: 'Reservar Ahora',
				entregar_mismo_lugar:'Entregar en el mismo lugar',
				franquicia:'Franquicia',
				vehiculo:'Vehículo',
				deducible:'Seguro con Deducible Reducido al 50%',
				deducible_helper:'El Seguro con Deducible Reducido es los días del alquiler {dias} más {dias_seguro} por un costo de {cost} por día para un total de {total}',
				quote:'En los alquileres con kilometraje limitado, el costo por km adicional es de U$S 0.25',
				settlement:'Por encima de {0} días de alquiler el precio queda a convenir con la rentadora',
				dias_alquiler:'Días de alquiler',
				extras:'Extras Disponibles',
				tolerancia:'Si supera las {tolerance} horas de devolución, se le sumará un día más de alquiler',
				detalles:'Detalles'
			},
			placeholder: {
				//Contacto
				nombre: 'Nombre',
				apellido: 'Apellido',
				pais: 'País',
				ciudad: 'Ciudad',
				feedback: 'Comentarios',
				email: 'Correo Eléctronico',
				query: 'Consulta',
				telefono: 'Teléfono',
				direccion: 'Dirección',
				mensaje: 'Mensaje',
				enviar: 'Enviar',
				//Filters
				tipo_vehiculo: 'Seleccione',
				consultar: 'Ver Opciones',
				//Subscription
				subscription: 'Ingresa tu email',
                empty: 'No hay opciones disponibles'
            },
		},
		item: {
			price_label: 'Por día',
			personas: 'Pasajeros',
			puertas: 'Puertas',
			rendimiento: 'Rendimiento',
			km: 'Km',
			motor: 'Motor',
			combustible: 'Combustible',
			transmision: 'Transmisión',
			transmision_automatica: 'Automática',
			transmision_manual: 'Manual',
			extras: 'Extras Disponibles',
			leer_mas: 'Solicitar Reserva'
		},
		ficha: {
			auto: {
				km: 'Kilometros',
				transmision: 'Transmisión',
				transmision_automatica: 'Automática',
				transmision_manual: 'Manual',
				pasajeros: 'Pasajeros',
				puertas: 'Puertas',
				motor: 'Motor',
				combustible: 'Combustible',
				rendimiento: 'Rendimiento',
				garantia: 'Garantía',
				nombre:'{nombre} o similar',
			},
		},
		dias:{
			1:'Lunes',
			2:'Martes',
			3:'Miércoles',
			4:'Jueves',
			5:'Viernes',
			6:'Sábado',
			7:'Domingo'
		},
		cero_producto: 'En estos momentos no tenemos productos para ofertar',
		required: 'requerido'
	},
	uk: {
		validaciones: {
			custom: {
				nombre: {
					required: 'Please enter your name'
				},
				apellido: {
					required: 'Please enter your last name'
				},
				direccion: {
					required: 'Please enter your address'
				},
				ciudad: {
					required: 'Please enter the city in which it is located'
				},
				departamento: {
					required: 'Please enter the department where you reside'
				},
				codigo_postal: {
					required: 'Please enter your zip code'
				},
				envio_depto: {
					required: 'Please enter the department for shipments'
				},
				envio_ciudad: {
					required: 'Please enter the city destined for shipments'
				},
				envio_dir: {
					required: 'Please enter the address for shipments'
				},
				envio_cod_postal: {
					required: 'Please enter the zip code located for shipments'
				},
				email: {
					required: 'Please give an E-mail address',
					email: 'Invalid email address'
				},
				cedula: {
					required: 'Please enter your ID',
					regex: 'The format is incorrect, the syntax must be: #. ###. ### - #'
				},
				asunto: {
					required: 'Please enter the subject of your message',
				},
				consulta: {
					required: 'Please enter your inquiry',
				},
				telefono: {
					required: 'Please enter your phone number',
					numeric: 'The telephone number must consist only of numbers'
				},
				celular: {
					required: 'Please enter your mobile number',
					regex: 'The format is incorrect, the syntax must be: 09 # ### ###'
				},
				password: {
					required: 'Please enter a password.',
					min: 'Your password must have a minimum of 8 characters'
				},
				confirm: {
					required: 'Please enter a confirmation password',
					confirmed: 'Passwords do not match'
				}
			}
		},
        errors:{
            no_minimo_dias_alquilar:'Minimum days to rent: {0}',
            restriccion:'Restriction',
			lugar:'The {0} selected is not opened {1} {2}',
			lugar_fuera_horario:{
            	entrega:'{0} does not deliver out of office hours {1}:00 H',
            	devolucion:'{0} does not allow devolutions out of office hours {1}:00 H'
			},
			rango_fecha_incorrecto:'El rango de fechas seleccionado es incorrecto',
			min_dias_x_lugar:'Days of rent has to be over {0} days to return the vehicle to {1}'
        },
		labels:{
			garantia:'If the driver is under 23 years old or the driving license is less than two years old, the amount of the guarantee will be increased by 50%'
		},
		form: {
			label: {
				//aside
				precio_x_dia: 'Price per day',
				transmision: 'Transmition',
				limpiar_filtros:'Clear Filters',
				//checkboxes
				manual: 'Manual',
				automatica: 'Automatic',
				//locations
				lugar_entrega: 'Place of Delivery',
				lugar_devolucion: 'Place of Return',
				fecha_entrega: 'Date of Delivery',
				fecha_devolucion: 'Date of Return',
				hora_entrega: 'Time of Delivery',
				hora_devolucion: 'Time of Return',
				//Subscription
				label_subscription: 'Subscribe and get information',
				//paginator
				anterior: 'Previous',
				siguiente: 'Next',
				//Feedback
				title: 'Personal Data',
				reservar: 'Reserve Now',
				entregar_mismo_lugar:'Deliver at same place',
				franquicia:'Franchise',
				vehiculo:'Vehículo',
				deducible:'Reduced Deductible Insurance at 50%',
				deducible_helper:'Reduced Deductible Insurance is the days of the rent {dias} plus {dias_seguro} for a cost of {cost} per day for a total of {total}',
				quote:'For rentals with limited mileage, the cost per additional km is U$S 0.25',
				settlement:'Above {0} days of rent the final price is fixed with the leasing',
				dias_alquiler:'Days for rent',
				extras:'Available Extras',
				tolerancia:'If you pass {tolerance} hours at devolution of the vehicle, it will sum another day of rent',
				detalles:'Details'
			},
			placeholder: {
				//Contacto
				nombre: 'Name',
				apellido: 'Last Name',
				pais: 'Country',
				ciudad: 'City',
				feedback: 'Feedback',
				email: 'E-mail',
				query: 'Query',
				telefono: 'Phone',
				direccion: 'Address',
				mensaje: 'Message',
				enviar: 'Send',
				//Filters
				tipo_vehiculo: 'Select',
				consultar: 'See Options',
				//Subscription
				subscription: 'Type your email',
                empty: 'There are no options available'
            },
		},
		item: {
			price_label: 'Per day',
			personas: 'Passengers',
			puertas: 'Doors',
			rendimiento: 'Performance',
			km: 'Km',
			motor: 'Engine',
			combustible: 'Fuel',
			transmision: 'Transmition',
			transmision_automatica: 'Automatic',
			transmision_manual: 'Manual',
			extras: 'Available Extras',
			leer_mas: 'Request Reservation'
		},
		ficha: {
			auto: {
				km: 'Kilometers',
				transmision: 'Transmition',
				transmision_automatica: 'Automatic',
				transmision_manual: 'Manual',
				pasajeros: 'Passengers',
				puertas: 'Doors',
				motor: 'Engine',
				combustible: 'Fuel',
				rendimiento: 'Performance',
				garantia: 'Assurance',
				nombre:'{nombre} or similar'
			},
		},
		dias:{
			1:'Monday',
			2:'Tuesday',
			3:'Wednesday',
			4:'Thursday',
			5:'Friday',
			6:'Saturday',
			7:'Sunday'
		},
		cero_producto: 'En estos momentos no tenemos productos para ofertar',
		required: 'required',
	}
};