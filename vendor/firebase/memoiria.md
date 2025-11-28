IES Ítaca  

 

Ciclo Formativo: Desarrollo de Aplicaciones Web 

 

 

 

 

MecaLink: < Plataforma Colaborativa para Talleres Mecánicos > 

 

Autor: SOUFIANE SAMRI 

 Correo Electrónico: 0471SSAMRI@e-itaca.es  

Tutor: ANGEL LUIS PERNIA CALVO 

Curso: 2025/2026 

Fecha: Diciembre 2025 

 

 

 

El presente Trabajo de Fin de Grado tiene como objetivo el diseño y desarrollo de una plataforma web colaborativa orientada a talleres mecánicos y profesionales del sector automotriz. Esta herramienta digital busca facilitar el intercambio de conocimientos técnicos, la resolución de averías y la publicación de contenidos especializados entre usuarios registrados. 

La plataforma, denominada MecaLink, permite a los mecánicos y talleres compartir experiencias reales, publicar problemas técnicos, proponer soluciones prácticas y acceder a artículos técnicos o tutoriales. Además, incorpora funcionalidades como la valoración de respuestas, la gestión de perfiles profesionales, y la recepción de notificaciones automáticas sobre temas relevantes. 

El sistema contempla distintos tipos de usuarios con permisos específicos: mecánicos, talleres y administradores. Cada uno de ellos podrá interactuar con la plataforma según su rol, contribuyendo al funcionamiento de una comunidad técnica activa y organizada. 

A lo largo del proyecto se ha trabajado en la definición funcional del sistema, el diseño de la arquitectura técnica, la implementación del backend y frontend, y la integración de servicios externos como la búsqueda por número VIN, que permite extraer automáticamente los datos del vehículo al publicar o consultar problemas. 

Este documento recoge el proceso completo de desarrollo, desde la idea inicial hasta la validación del prototipo, incluyendo los retos superados, las herramientas utilizadas y las decisiones clave tomadas durante la ejecución. 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

Descripción del proyecto  

1.1. Contexto del proyecto   

1.1.1. Ámbito y entorno   

1.1.2. Análisis de la realidad   

1.1.2.1. Autodata    

1.1.2.2. HaynesPro     

1.1.2.3. Identifix     

1.1.2.4. iATN     

1.1.2.5. RepairPal    

1.1.2.6. ZPK VIN Analyzer    

1.1.3. Solución y justificación de la solución propuesta   

1.1.4. Destinatarios  

1.2. Objetivos del proyecto   

1.3. Objetivo del proyecto en lengua extranjera   

1.4. Marco legal 

Acuerdo del proyecto  

2.1. Requisitos funcionales y no funcionales 

2.2. Limitaciones y consideraciones para el MVP   

2.3. Tareas   

2.4. Metodología a seguir para la realización del proyecto   

2.5. Planificación temporal de tareas   

2.6. Presupuesto (gastos, ingresos, beneficio)   

2.7. Contrato/Pliego de condiciones   

2.8. Análisis de riesgos 

Documento de análisis y diseño   

3.1. Análisis y diseño de la arquitectura de la aplicación   

 3.1.1. Capa de presentación con React.js    

     3.1.2. Capa de lógica de negocio con PHP    

 3.1.2.1. Diseño de API: REST API   

 3.1.3. Integración con servicios y APIs externas (VIN, notificaciones)  

3.2. Tecnologías/Herramientas usadas y descripción   

3.3. Arquitectura de componentes  

3.4. Modelado de datos    

     3.4.1. Base de datos relacional (MySQL)    

     3.4.2. Tablas principales: usuarios, problemas, soluciones, artículos, notificaciones   

 3.4.3. Inserción de datos mediante Seed   

3.5. Análisis y diseño del sistema funcional   

3.6. Análisis y diseño de la interfaz de usuario   

3.7. Wireframing y cardflow   

3.8. Identidad visual   

3.9. Prototipos de alta fidelidad  

3.10. Librerías UI (Bootstrap, React Icons) 

Documento de implementación e implantación del sistema  

 4.1. Implementación  

 4.2. Instalación y configuración  

 4.3. Pruebas  

 4.4. Manual de usuario 

Documento de cierre   

5.1. Resultados obtenidos y conclusiones  

 5.2. Diario de bitácora   

5.3. Temporalización y desviación sobre la planificación inicial 

Bibliografía 

Índice de tablas y figuras 

Índice de tablas 

Anexos 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

 

1. DESCRIPCIÓN DEL PROYECTO 

El presente Trabajo de Fin de Grado tiene como objetivo el diseño y desarrollo de una plataforma web colaborativa orientada a talleres mecánicos y profesionales del sector automotriz. Esta herramienta digital busca facilitar el intercambio de conocimientos técnicos, la resolución de averías y la publicación de contenidos especializados entre usuarios registrados. 

La plataforma, denominada MecaLink, permite a los mecánicos y talleres compartir experiencias reales, publicar problemas técnicos, proponer soluciones prácticas y acceder a artículos técnicos o tutoriales. Además, incorpora funcionalidades como la valoración de respuestas, la gestión de perfiles profesionales, y la recepción de notificaciones automáticas sobre temas relevantes. 

El sistema contempla distintos tipos de usuarios con permisos específicos: mecánicos, talleres y administradores. Cada uno de ellos podrá interactuar con la plataforma según su rol, contribuyendo al funcionamiento de una comunidad técnica activa y organizada. 

A lo largo del proyecto se ha trabajado en la definición funcional del sistema, el diseño de la arquitectura técnica, la implementación del backend y frontend, y la integración de servicios externos como la búsqueda por número VIN(Número de Identificación del Vehículo), que permite extraer automáticamente los datos del vehículo al publicar o consultar problemas. 

Este documento recoge el proceso completo de desarrollo, desde la idea inicial hasta la validación del prototipo, incluyendo los retos superados, las herramientas utilizadas y las decisiones clave tomadas durante la ejecución. 

 

1.1 CONTEXTO DEL PROYECTO 

En los últimos años, el sector automotriz ha experimentado una evolución tecnológica significativa, tanto en los sistemas de diagnóstico como en la gestión de talleres. Sin embargo, muchos profesionales siguen enfrentando dificultades técnicas que podrían resolverse más eficientemente mediante el intercambio de conocimientos entre expertos. 

La falta de plataformas digitales especializadas para la colaboración técnica entre mecánicos y talleres genera una oportunidad clara para el desarrollo de soluciones que centralicen la experiencia del sector. En este contexto, surge MecaLink, una plataforma web colaborativa que busca conectar a profesionales de la mecánica para compartir problemas reales, soluciones prácticas y contenidos técnicos. 

El proyecto se enmarca dentro del ámbito de la formación profesional en desarrollo de aplicaciones web, y responde a una necesidad detectada en el entorno laboral: mejorar la comunicación técnica entre talleres, fomentar el aprendizaje mutuo y facilitar el acceso a información especializada. 

1.1.1 ÁMBITO Y ENTORNO 

El proyecto MecaLink se sitúa en el ámbito del desarrollo de aplicaciones web orientadas a la colaboración profesional. En concreto, se enfoca en el sector de la mecánica automotriz, donde existe una necesidad creciente de digitalizar procesos de comunicación técnica entre talleres, mecánicos y expertos. 

El entorno actual está marcado por una evolución constante en los sistemas de diagnóstico, la complejidad de los vehículos modernos y la diversidad de problemas que enfrentan los profesionales del sector. A pesar de la existencia de herramientas de gestión interna en algunos talleres, no hay plataformas abiertas que permitan compartir experiencias, resolver dudas técnicas o acceder a contenidos especializados de forma colaborativa. 

MecaLink se plantea como una solución accesible, escalable y centrada en la comunidad, que aprovecha las tecnologías web modernas para conectar a los profesionales del sector y mejorar la eficiencia en la resolución de problemas mecánicos. 

 

1.1.2. 

Análisis de la realidad El sector de la automoción, especialmente en el ámbito de la reparación y el mantenimiento mecánico, ha experimentado una transformación progresiva en los últimos años. A pesar de los avances tecnológicos en diagnóstico y gestión de talleres, sigue existiendo una carencia significativa de herramientas digitales que fomenten la colaboración entre profesionales del sector. La mayoría de las soluciones actuales se centran en el acceso a bases de datos técnicas o manuales de reparación, pero no promueven el intercambio activo de experiencias ni la resolución colectiva de problemas reales. 

Estas herramientas ofrecen información precisa y estructurada, pero en muchos casos presentan interfaces complejas, poco intuitivas o cerradas, lo que limita su accesibilidad para pequeños talleres o mecánicos independientes. Para obtener una visión general sobre las funcionalidades de estas plataformas, se plantea analizar cuáles son sus características principales y qué las diferencia entre sí. 

En un estudio preliminar se han seleccionado ocho soluciones relevantes, tanto plataformas como servicios técnicos, que ofrecen acceso a información automotriz o fomentan la asistencia técnica. El objetivo es analizar el mercado actual de herramientas digitales disponibles para profesionales de la mecánica, evaluando su enfoque, modelo de acceso, funcionalidades colaborativas y nivel de apertura. 

Nos interesa analizar cómo estas plataformas permiten el acceso a información técnica, si contemplan la participación activa de los usuarios, la posibilidad de compartir experiencias o soluciones, y si integran servicios externos como APIs de identificación de vehículos mediante número VIN. Además, se evalúa si su acceso es gratuito, freemium (con funciones premium de pago) o requiere suscripción completa. 

1.1.2.1. 

Autodata Autodata es una plataforma técnica consolidada en el sector, desarrollada en el Reino Unido. Opera bajo un modelo de suscripción y ofrece acceso a manuales de reparación, esquemas eléctricos, tiempos de intervención y procedimientos técnicos. Su base de datos es extensa y está validada por fabricantes, pero no permite la interacción entre usuarios ni la publicación de casos reales. Su enfoque es unidireccional y no contempla funcionalidades colaborativas. 

1.1.2.2. 

HaynesPro HaynesPro, de origen neerlandés, proporciona información técnica para talleres mediante suscripciones. Incluye diagnósticos guiados, boletines técnicos y esquemas detallados. Aunque su contenido es de alta calidad, no permite la creación de comunidad ni el intercambio de experiencias entre profesionales. Su interfaz está orientada a usuarios con formación técnica avanzada. 

1.1.2.3. 

Identifix Identifix es una plataforma estadounidense que combina una base de datos técnica con un sistema de resolución de problemas basado en casos reales. Su modelo es de pago y está enfocado en talleres de Norteamérica. Aunque permite cierto nivel de colaboración, su acceso está restringido geográficamente y su coste puede ser elevado para pequeños talleres. 

1.1.2.4. 

iATN (International Automotive Technicians Network) iATN es una red global de técnicos automotrices que permite compartir casos, participar en foros y acceder a recursos técnicos. Su modelo es freemium, con funciones avanzadas bajo suscripción. Aunque destaca por su comunidad activa, su diseño visual es anticuado y su experiencia de usuario no está optimizada para dispositivos móviles, lo que limita su adopción por nuevos usuarios. 

1.1.2.5. 

RepairPal RepairPal ofrece estimaciones de precios de reparación y localización de talleres certificados. Está orientada principalmente a clientes finales, no a profesionales. No permite la publicación de problemas técnicos ni el acceso a contenidos especializados. Su valor reside en la transparencia de precios, pero no aporta funcionalidades colaborativas para mecánicos. 

1.1.2.6. 

ZPK VIN Analyzer ZPK VIN Analyzer es una API gratuita que permite obtener información técnica de un vehículo a partir de su número VIN. Su integración en plataformas como MecaLink permite enriquecer las publicaciones con datos precisos del vehículo (marca, modelo, año, tipo de motor), mejorando la calidad de las consultas y respuestas. No es una plataforma en sí misma, pero representa un recurso valioso para automatizar la identificación de vehículos. 

1.1.2.7. 

Foros independientes (Foromecanicos, MecánicaOnline, etc.) Existen múltiples foros en línea donde los mecánicos comparten dudas y soluciones. Aunque ofrecen un espacio de interacción, suelen carecer de estructura, control de calidad o validación de respuestas. Además, no cuentan con sistemas de reputación ni integración con herramientas técnicas, lo que limita su fiabilidad y escalabilidad. 

1.1.2.8. 

YouTube y redes sociales Muchos profesionales recurren a plataformas como YouTube, TikTok o grupos de Facebook para compartir vídeos de reparaciones o buscar soluciones. Aunque son accesibles y visuales, la información no siempre está verificada, y la búsqueda de contenido específico puede resultar poco eficiente. Tampoco existe una organización temática ni control de calidad sobre las respuestas. 

 

Plataforma 

Acceso técnico a vehículos 

Publicación de problemas reales 

Comunidad / Interacción 

Integración con VIN/API 

Ventajas Clave 

Limitaciones principales 

Autodata 

✅ Manuales, esquemas 

❌ No 

❌ No 

❌ No 

Fiabilidad: Base de datos oficial y muy precisa. 

Cerrado, sin interacción 

HaynesPro 

✅ Diagnóstico guiado 

❌ No 

❌ No 

❌ No 

Calidad: Ofrece diagnósticos guiados de alta calidad. 

Enfoque técnico avanzado 

Identifix 

✅ Casos + base técnica 

✅ Parcial 

✅ Sí 

❌ No 

Casos Reales: Su mayor valor es la base de datos de problemas reales resueltos. 

Acceso limitado geográfico 

iATN 

✅ Foros técnicos 

✅ Sí 

✅ Sí 

❌ No 

Comunidad: Red global de técnicos muy activa y colaborativa. 

Interfaz poco intuitiva 

RepairPal 

❌ Solo precios y talleres 

❌ No 

❌ No 

❌ No 

Transparencia: Útil para clientes (estimación de precios). 

No orientado a profesionales 

ZPK VIN Analyzer 

✅ Datos por VIN 

❌ No (solo API técnica) 

❌ No 

✅ Sí 

Accesibilidad: API fácil de integrar y con un plan gratuito. 

No es plataforma completa 

Foros independientes 

✅ Casos reales 

✅ Sí 

✅ Sí 

❌ No 

Volumen: Gratis y con un gran volumen de casos y opiniones. 

Sin estructura ni validación 

YouTube/redes sociales 

✅ Vídeos técnicos 

✅ Sí 

✅ Sí 

❌ No 

Visual: Muy accesible y útil para ver procedimientos paso a paso 

Información no verificada 

 

1.1.3 

Solución y justificación de la solución propuesta 

Tras el análisis de las herramientas existentes en el sector automotriz, se observa que la mayoría de las plataformas disponibles se centran en ofrecer información técnica estructurada, pero no contemplan la participación activa de los usuarios ni fomentan la colaboración entre profesionales. Esta carencia representa una oportunidad clara para el desarrollo de una solución que combine acceso técnico con interacción comunitaria. 

La propuesta de MecaLink surge como respuesta a esta necesidad. Se trata de una plataforma web colaborativa que permite a mecánicos y talleres compartir problemas reales, publicar soluciones prácticas, valorar respuestas útiles y acceder a contenidos técnicos especializados. A diferencia de las soluciones actuales, MecaLink pone el foco en la experiencia del usuario, la participación activa y la construcción de una comunidad técnica sólida. 

Además, la integración de servicios externos como la API ZPK VIN Analyzer permite enriquecer las publicaciones con datos precisos del vehículo, mejorando la calidad de las consultas y facilitando la búsqueda de soluciones específicas. Esta funcionalidad aporta un valor añadido al sistema, automatizando la identificación del vehículo y reduciendo errores en la descripción de averías. 

La solución propuesta se justifica por su capacidad de cubrir un vacío real en el sector, ofreciendo una herramienta accesible, escalable y alineada con las necesidades de los profesionales. MecaLink no solo mejora la eficiencia en la resolución de problemas, sino que también promueve el aprendizaje colectivo y la mejora continua dentro del ámbito de la mecánica automotriz. 

1.1.4 

Destinatarios 

La plataforma MecaLink está dirigida principalmente a profesionales del sector de la mecánica automotriz, incluyendo tanto a mecánicos independientes como a talleres de reparación de vehículos. Estos usuarios constituyen el núcleo funcional del sistema, ya que son quienes publican problemas técnicos, proponen soluciones, comparten experiencias y consultan artículos especializados. 

Además, el sistema contempla la figura del administrador, encargado de gestionar la comunidad, moderar contenidos y garantizar el correcto funcionamiento de la plataforma. Este perfil está orientado a usuarios con conocimientos técnicos y competencias en gestión digital. 

De forma secundaria, MecaLink también puede resultar útil para estudiantes de formación profesional en mecánica, docentes del área técnica, y empresas del sector interesadas en observar tendencias, problemas frecuentes o soluciones innovadoras aplicadas en el entorno real. 

En resumen, los destinatarios del proyecto se agrupan en tres perfiles principales: 

Usuarios técnicos (mecánicos y talleres): Publican, consultan y colaboran activamente. 

Administradores: Supervisan, moderan y configuran el sistema. 

Usuarios observadores (estudiantes, docentes, empresas): Acceden a contenidos y tendencias sin intervenir directamente. 

Esta segmentación permite adaptar la experiencia de usuario según el rol, garantizando una plataforma funcional, segura y orientada a la colaboración profesional. 

 

1.2 Objetivos del proyecto 

El objetivo principal del proyecto MecaLink es desarrollar una plataforma web colaborativa que facilite la comunicación técnica entre profesionales del sector automotriz, permitiendo compartir problemas reales, soluciones prácticas y contenidos especializados de forma estructurada y accesible. 

Este objetivo general se desglosa en los siguientes objetivos específicos: 

Diseñar una arquitectura modular y escalable que permita el crecimiento futuro de la plataforma sin comprometer su rendimiento. 

Implementar un sistema de registro y autenticación con roles diferenciados (mecánico, taller, administrador) para garantizar la seguridad y personalización de la experiencia. 

Desarrollar funcionalidades de publicación y respuesta que permitan a los usuarios compartir problemas técnicos y proponer soluciones verificables. 

Integrar una API externa de análisis VIN para enriquecer las publicaciones con datos técnicos precisos del vehículo. 

Crear un sistema de reputación y valoración que incentive la participación activa y la calidad de las respuestas. 

Diseñar una interfaz intuitiva y funcional, adaptada a dispositivos móviles y ordenadores, que facilite la navegación y el acceso a contenidos. 

Establecer mecanismos de moderación y control para garantizar la calidad del contenido y el respeto entre usuarios. 

Documentar el proceso completo de desarrollo, incluyendo decisiones técnicas, herramientas utilizadas, retos superados y evolución del sistema. 

Estos objetivos permiten abordar el proyecto desde una perspectiva técnica, funcional y colaborativa, alineada con las necesidades reales del sector mecánico y con los principios del desarrollo de software profesional. 

1.3 Project Objective in a Foreign Language (English) 

The main objective of the MecaLink project is to develop a collaborative web platform that facilitates technical communication among automotive professionals. The system allows users to publish real mechanical problems, share practical solutions, and access specialized content in a structured and accessible way. 

MecaLink is designed to connect mechanics and workshops through a digital environment that promotes knowledge exchange, collective learning, and efficient problem-solving. The platform integrates external services such as a VIN analysis API to automatically enrich vehicle-related posts with accurate technical data. 

The project aims to deliver a scalable, user-friendly, and community-driven solution that reflects the real needs of the automotive repair sector. It also represents a learning process in software development, from initial concept to final implementation, combining backend architecture, user interface design, and collaborative features. 

 

1.4 

Marco legal 

El desarrollo de la plataforma MecaLink se enmarca dentro de la normativa legal vigente en España y la Unión Europea en materia de protección de datos, propiedad intelectual y servicios digitales. Dado que el sistema permite el registro de usuarios, la publicación de contenidos técnicos y la gestión de información relacionada con vehículos, es fundamental garantizar el cumplimiento de los requisitos legales aplicables. 

🔹 Protección de datos personales 

La plataforma cumple con el Reglamento General de Protección de Datos (RGPD), que regula el tratamiento de datos personales en la Unión Europea. Se han implementado medidas técnicas y organizativas para garantizar la confidencialidad, integridad y disponibilidad de los datos, incluyendo cifrado de contraseñas, validación de formularios y control de acceso por roles. 

Además, se contempla el cumplimiento de la Ley Orgánica 3/2018, de Protección de Datos Personales y garantía de los derechos digitales (LOPDGDD), que complementa el RGPD en el contexto español. Los usuarios pueden ejercer sus derechos de acceso, rectificación, cancelación y oposición (ARCO) mediante solicitud directa a los administradores de la plataforma. 

🔹 Propiedad intelectual y contenidos 

Los contenidos publicados por los usuarios (problemas, soluciones, artículos técnicos) se consideran aportaciones voluntarias. La plataforma establece en sus condiciones de uso que los autores conservan la propiedad intelectual de sus publicaciones, pero autorizan su difusión dentro del entorno de MecaLink. Se prohíbe la publicación de material protegido por derechos de autor sin autorización expresa. 

🔹 Responsabilidad y moderación 

La plataforma incluye mecanismos de moderación para prevenir la publicación de contenidos ofensivos, falsos o peligrosos. El administrador tiene la facultad de eliminar publicaciones que infrinjan las normas de uso o que puedan comprometer la seguridad de los usuarios. Se establece un sistema de reporte comunitario para facilitar la detección de irregularidades. 

🔹 Integración de servicios externos 

La integración de la API ZPK VIN Analyzer se realiza conforme a sus términos de uso públicos, sin almacenamiento de datos sensibles ni identificación personal. La consulta de datos técnicos del vehículo se limita a información no vinculada directamente con el usuario. 

2. Acuerdo del proyecto 

2.1 

Requisitos funcionales y no funcionales 

Para garantizar el correcto funcionamiento de la plataforma MecaLink, se han definido una serie de requisitos funcionales y no funcionales que orientan el diseño, desarrollo e implementación del sistema. Estos requisitos se han establecido en base a las necesidades detectadas en el análisis del contexto, los objetivos del proyecto y las expectativas de los usuarios destinatarios. 

🔹 Requisitos funcionales 

Los requisitos funcionales definen las acciones que el sistema debe ser capaz de realizar: 

RF1. Registro de usuarios: El sistema debe permitir el registro de nuevos usuarios con validación de datos y asignación de roles (mecánico, taller, administrador). 

RF2. Inicio de sesión: El sistema debe permitir a los usuarios autenticarse mediante correo electrónico y contraseña. Se exige una contraseña compleja (mínimo 8 caracteres, combinando letras y números) durante el registro. Para garantizar la seguridad, las contraseñas se almacenan de forma cifrada en la base de datos utilizando un algoritmo de hashing unidireccional (concretamente password_hash() de PHP), lo que asegura que ni siquiera los administradores puedan ver la contraseña original. 

 

RF3. Publicación de problemas técnicos: Los usuarios registrados (mecánicos y talleres) deben poder crear nuevas publicaciones de averías. El formulario de publicación debe contar con campos estructurados que organicen la información de manera eficiente. Estos campos se agrupan en: 

Datos de la Avería: Título del problema y Descripción detallada. 

Categorización: Selección de una categoría (p.ej., Motor, Electricidad, Transmisión, Frenos, etc.). 

Datos del Vehículo: Un campo único para el Número de Identificación del Vehículo (VIN). 

 

RF4. Consulta de problemas: Todos los usuarios registrados deben poder visualizar y filtrar publicaciones por categoría, marca, modelo o tipo de avería. 

RF5. Respuestas y soluciones: Los usuarios deben poder responder a publicaciones con soluciones técnicas, incluyendo texto, enlaces o referencias. 

RF6. Valoración de respuestas: El sistema debe incluir un mecanismo de reputación para identificar las soluciones más fiables. En lugar de una simple valoración "útil/inútil", se implementa un sistema de votación ponderada (Up/Down), similar al de plataformas como Stack Overflow. Los usuarios pueden votar positivamente (Upvote) o negativamente (Downvote) cada respuesta. La suma de estos votos genera la reputación del usuario que aportó la solución y ayuda a ordenar las respuestas, mostrando las más valoradas primero. 

 

RF7. Gestión de usuarios y contenidos: El administrador debe poder gestionar usuarios, eliminar publicaciones inapropiadas y configurar categorías. 

RF8. Integración con API VIN: El sistema debe consultar automáticamente la API ZPK VIN Analyzer para extraer datos del vehículo a partir del número VIN. 

RF9. Notificaciones: El sistema debe enviar notificaciones a los usuarios cuando reciban respuestas o interacciones relevantes. 

🔹 Requisitos no funcionales 

Los requisitos no funcionales definen las características de calidad del sistema: 

RNF1. Usabilidad: La interfaz debe ser clara, intuitiva y accesible desde dispositivos móviles y ordenadores. 

RNF2. Seguridad: La plataforma debe garantizar la integridad y confidencialidad de los datos. Esto se logra mediante: 

Hashing de contraseñas: Uso de password_hash() (PHP) para almacenar contraseñas de forma irreversible. 

Autenticación basada en Tokens (JWT): Tras el inicio de sesión, el backend genera un JSON Web Token (JWT) que se almacena en el cliente. Este token debe enviarse en las cabeceras de cada solicitud a la API para validar la sesión del usuario. 

Control de Acceso Basado en Roles (RBAC): El backend debe verificar el rol del usuario (extraído del JWT) antes de permitir el acceso a rutas sensibles (p.ej., solo un rol admin puede acceder al AdminPanel). 

Protección contra Inyección: Uso de consultas preparadas (Prepared Statements) de PDO en PHP para interactuar con la base de datos MySQL, previniendo ataques de Inyección SQL. 

CORS: Configuración de cabeceras CORS (Cross-Origin Resource Sharing) en el backend (PHP) para permitir solicitudes únicamente desde el dominio del frontend (React). 

 

RNF3. Rendimiento: El sistema debe responder en menos de 2 segundos para operaciones comunes (registro, login, consulta). 

RNF4. Escalabilidad: La arquitectura debe permitir la incorporación de nuevas funcionalidades sin afectar el rendimiento. 

RNF5. Mantenibilidad: El código debe estar modularizado y documentado para facilitar futuras actualizaciones. 

RNF6. Disponibilidad: El sistema debe estar disponible al menos el 95% del tiempo durante el periodo de pruebas. 

RNF7. Compatibilidad: La plataforma debe ser compatible con los navegadores más comunes (Chrome, Firefox, Edge). 

2.2 

Limitaciones y Consideraciones para el MVP 

Durante la fase de diseño del proyecto MecaLink, se ha definido un conjunto de funcionalidades mínimas viables (MVP) que permiten validar el concepto, probar la experiencia de usuario y garantizar la operatividad básica del sistema. Estas funcionalidades han sido seleccionadas en base a criterios de viabilidad técnica, impacto funcional y tiempo de desarrollo disponible. 

🔹 Limitaciones del MVP 

El sistema no incluye aún un módulo de mensajería privada entre usuarios. 

No se contempla la subida de archivos multimedia (imágenes, vídeos) en las publicaciones. 

El sistema de reputación está limitado a votaciones simples (útil/no útil), sin niveles ni insignias. 

Las notificaciones se gestionan de forma básica (sin configuración avanzada ni historial). 

La interfaz está optimizada para escritorio, con adaptación parcial a dispositivos móviles. 

La integración con otras APIs externas (manuales técnicos, bases de datos OEM) queda fuera del alcance del MVP. 

🔹 Funcionalidades incluidas en el MVP 

Funcionalidad 

Estado en el MVP 

Observaciones técnicas 

Registro y login de usuarios 

✅ Incluido 

Validación de datos y roles diferenciados 

Publicación de problemas técnicos 

✅ Incluido 

Campos estructurados + integración VIN API 

Consulta de publicaciones 

✅ Incluido 

Filtros por categoría, marca, modelo 

Respuestas y soluciones 

✅ Incluido 

Comentarios abiertos con validación 

Valoración de respuestas 

✅ Incluido 

Votación útil/no útil 

Panel de administración 

✅ Incluido 

Gestión de usuarios y contenidos 

Integración con ZPK VIN Analyzer 

✅ Incluido 

Consulta automática por número VIN 

Interfaz web 

✅ Incluido 

Diseño funcional, adaptable parcialmente a móvil 

2.3 

Tareas 

A partir de los requisitos funcionales y no funcionales definidos en el proyecto, se ha estimado un conjunto inicial de tareas clave necesarias para el desarrollo de la plataforma MecaLink. Estas tareas abarcan desde la implementación de funcionalidades críticas como el registro de usuarios y la publicación de problemas técnicos, hasta aspectos no funcionales como la seguridad, la escalabilidad y la integración con servicios externos. Es importante destacar que esta lista podrá ajustarse y evolucionar conforme el proyecto avance y se concreten detalles técnicos, permitiendo una mayor adaptación a las necesidades reales del sector. 

 

 

Código 

Tarea 

Descripción detallada 

RF-01 

Implementación del formulario de registro 

Desarrollo del formulario en React con validaciones para correo y contraseña. 

RF-01 

Sistema de autenticación 

Programación de login seguro con cifrado de contraseñas y gestión de sesiones. 

RF-02 

Publicación de problemas técnicos 

Creación de formulario estructurado para publicar averías con campos técnicos. 

RF-02 

Integración con API VIN Analyzer 

Consulta automática de datos del vehículo mediante número VIN.(Número de Identificación del Vehículo (NIV))  

RF-02 

Visualización de datos del vehículo 

Mostrar marca, modelo, año y tipo de motor en la publicación tras análisis VIN. 

RF-03 

Sistema de respuestas 

Desarrollo de módulo para responder problemas con soluciones técnicas verificables. 

RF-03 

Valoración de respuestas 

Implementación de votación útil/no útil para generar reputación entre usuarios. 

RF-04 

Panel de administración 

Desarrollo de interfaz para gestionar usuarios, categorías y contenidos. 

RF-05 

Diseño de interfaz web 

Creación de vistas en React adaptadas a escritorio y móviles. 

RF-05 

Navegación por categorías 

Implementación de filtros por marca, tipo de avería, modelo y fecha. 

RF-06 

Sistema de notificaciones 

Envío de alertas automáticas por nuevas respuestas o actividad relevante. 

RF-07 

Módulo de artículos técnicos 

Desarrollo de sección para subir y consultar artículos, tutoriales o enlaces útiles. 

RF-08 

Gestión de perfiles 

Implementación de edición de perfil con nombre, descripción y rol técnico. 

RF-09 

Moderación de contenidos 

Programación de funciones para eliminar publicaciones inapropiadas o duplicadas. 

RF-10 

Sistema de búsqueda avanzada 

Desarrollo de filtros combinables para refinar resultados por múltiples criterios. 

RNF-01 

Seguridad y cifrado 

Aplicación de protocolos de seguridad (hashing, validación, control de acceso). 

RNF-02 

Arquitectura escalable 

Definición de estructura modular en PHP con controladores independientes. 

RNF-03 

Documentación técnica 

Redacción de manuales, diagramas y anexos para facilitar mantenimiento futuro. 

 

2.4 

Metodología a seguir para la realización del proyecto 

Para el desarrollo del proyecto MecaLink se ha adoptado una metodología de trabajo ágil, basada en principios de iteración, modularidad y validación progresiva. Esta metodología permite adaptar el proceso a los cambios técnicos, gestionar mejor los tiempos y asegurar la calidad del producto final. 

Se ha optado por una estructura de trabajo inspirada en el modelo incremental, donde cada módulo funcional se desarrolla, prueba y valida de forma independiente antes de integrarse al sistema completo. Esta estrategia facilita la detección temprana de errores, mejora la mantenibilidad del código y permite realizar ajustes conforme evolucionan los requisitos. 

🔹 Fases metodológicas 

Fase 

Objetivo principal 

Actividades clave 

Análisis 

Comprender el contexto y definir requisitos 

Estudio del sector, análisis de plataformas, definición de funcionalidades 

Diseño técnico 

Planificar la arquitectura y estructura del sistema 

Modelado de base de datos, diseño de API REST, definición de roles 

Desarrollo 

Implementar los módulos funcionales del MVP 

Programación backend y frontend, integración VIN API, validaciones 

Pruebas 

Verificar el funcionamiento y corregir errores 

Pruebas unitarias, revisión de flujos, ajustes técnicos 

Documentación 

Redactar el informe académico y anexos técnicos 

Estructura del TFG, tablas comparativas, capturas y diagramas 

Presentac-ión final 

Preparar la defensa oral y entrega del proyecto 

Diseño de diapositivas, resumen ejecutivo, simulación de exposición 

🔹 Herramientas utilizadas 

Lenguaje backend: PHP (estructura modular con controladores) 

Base de datos: MySQL (modelo relacional) 

Frontend: HTML/CSS + React (interfaz adaptable) 

Control de versiones: Git (repositorio privado) 

API externa: ZPK VIN Analyzer (consulta técnica por número VIN) 

Documentación: Word + Figma (diagramas, tablas, interfaz) 

Esta metodología permite mantener un flujo de trabajo ordenado, con entregables claros en cada fase, y facilita la adaptación a nuevas necesidades sin comprometer la estabilidad del sistema. 

 

2.5  Planificación temporal de tareas 

El desarrollo del proyecto MecaLink se organizó mediante una planificación intensiva basada en principios de la metodología ágil Scrum. Se definieron tres sprints principales, ajustados al calendario real del proyecto (del 20 de septiembre al 1 de diciembre de 2025). Esta estructura permitió dividir el trabajo en fases manejables, facilitar el seguimiento del progreso y mantener un ritmo constante de desarrollo. 

Se prestó especial atención a la aparición de tareas bloqueantes, como la integración de la API VIN o la validación de formularios, que podrían afectar el flujo de trabajo. Para minimizar riesgos, cada sprint se diseñó procurando que las tareas fuesen lo más independientes posible. 

🌀 Análisis y planificación 

Fechas: 20 septiembre – 04 octubre 2025 

Este primer sprint se centró en establecer los fundamentos técnicos y conceptuales del proyecto MecaLink. Se definieron los requisitos funcionales y no funcionales, se analizaron plataformas similares en el sector automotriz, y se diseñó la arquitectura inicial del sistema, incluyendo la estructura de base de datos y los componentes backend. Paralelamente, se inició la redacción de la memoria académica, con especial atención al marco teórico y la contextualización del proyecto. Esta fase permitió consolidar los objetivos, delimitar el alcance y preparar el entorno de trabajo para los siguientes sprints. 

 

 

 

🌀  Desarrollo funcional del MVP 

Fechas: 7 octubre – 02 noviembre 2025 

Durante este segundo sprint se abordó la implementación de las funcionalidades principales de la plataforma. Se desarrollaron los módulos de registro e inicio de sesión, la publicación de problemas técnicos, y la integración con la API VIN Analyzer para enriquecer los datos de los vehículos. También se programó el sistema de respuestas y votaciones, así como el panel de administración para la gestión de usuarios y contenidos. Esta fase se centró en construir un producto mínimo viable (MVP) funcional, que permitiera validar la lógica del sistema y realizar pruebas internas. 

 

🌀 Documentación y entrega final 

Fechas: 11 noviembre – 05 diciembre 2025 

El último sprint se dedicó a la finalización de la documentación académica y la preparación de la entrega del proyecto. Se redactaron los apartados restantes del TFG, se organizaron los anexos técnicos (capturas, diagramas, tablas), y se revisaron todos los contenidos para asegurar coherencia y calidad. Además, se realizó una simulación de defensa oral, con diseño de diapositivas y resumen ejecutivo. Esta fase cerró el ciclo de desarrollo, consolidando tanto el producto técnico como el trabajo académico 

. 

2.6 

Presupuesto (gastos, ingresos, beneficio) 

Aunque el proyecto MecaLink se desarrolla en el marco de un trabajo académico, se ha realizado una estimación presupuestaria orientativa que contempla los costes técnicos indirectos, así como el valor económico del tiempo invertido. Esta valoración permite proyectar la viabilidad del sistema en caso de implementación real y reconocer el esfuerzo profesional asociado al desarrollo. 

🔹 Costes mensuales estimados 

Concepto 

Coste mensual (€) 

Notas 

Electricidad 

10 

Consumo eléctrico estimado del ordenador (uso personal). 

Ordenador 

50 

Depreciación mensual del equipo valorado en 1000 €, vida útil 20 meses. 

Internet/WiFi 

20 

Coste mensual estimado de banda ancha doméstica para desarrollo y pruebas. 

Dispositivos móviles 

5 

Depreciación mensual del móvil de prueba valorado en 120 €, vida útil 24 meses. 

Software y Cloud 

0 

Uso exclusivo de herramientas gratuitas y planes free tier. 

Cursos/Formación 

0 

Formación previa no imputada como coste específico para el proyecto. 

Coste total aproximado por tres meses no imputado: 255 € 

🔹 Valoración económica del tiempo invertido 

Concepto 

Horas estimadas 

Tarifa/hora (€) 

Total (€) 

Desarrollo backend (PHP) 

180 

12 

2160 

Diseño de base de datos 

40 

12 

480 

Integración API VIN 

30 

12 

360 

Documentación académica 

30 

12 

360 

Revisión y pruebas 

40 

12 

480 

Total horas coste: 320 horas → 3840 € 

Nota: La tarifa por hora se ajusta al promedio de un desarrollador backend junior en España con stack PHP/MySQL. 

🔹 Resumen económico 

Concepto 

Total (€) 

Comentarios 

Costes directos 

255 

Imputados, no desembolsados realmente. 

Valor del trabajo (3 meses) 

3840 

Valor profesional estimado del esfuerzo invertido. 

Total estimado 

4095 

Suma de costes directos + valor del trabajo (IVA incl.) 

 

2.7 

Contrato / Pliego de condiciones 

El documento de Términos y Condiciones (véase anexo II) establece las normas que los usuarios deben aceptar al utilizar la plataforma MecaLink. Su principal objetivo es regular el uso adecuado del sistema, garantizar la seguridad tanto para los usuarios como para la aplicación, y definir las responsabilidades de cada parte implicada. Este marco legal es esencial para el correcto funcionamiento de la plataforma y para proteger los derechos tanto del desarrollador como de los usuarios registrados. 

El documento ha sido redactado de forma clara, accesible y conforme a la legislación vigente, incluyendo aspectos relacionados con la privacidad, el tratamiento de datos personales, la interacción con el contenido técnico, y las responsabilidades derivadas del uso de la plataforma. También contempla directrices específicas para el manejo de contenido generado por los usuarios, la gestión de publicidad técnica, y el uso de tecnologías integradas como la API VIN Analyzer. 

Entre los puntos más relevantes del pliego de condiciones destacan los siguientes: 

🔹 Derechos de Propiedad Intelectual (apartado 2) 

Todo el contenido propio de MecaLink —incluyendo el logotipo, la interfaz, el diseño de base de datos y los algoritmos de análisis— está protegido legalmente. Se prohíbe cualquier uso no autorizado por parte de terceros, incluyendo la reproducción, modificación o distribución sin consentimiento expreso. 

🔹 Actividades prohibidas (apartados 5 y 5.1) 

Se sancionan prácticas como la publicación de contenido ofensivo, difamatorio, ilegal o que infrinja derechos de autor. También se prohíbe el uso automatizado de la plataforma mediante bots, scrapers o herramientas que alteren el funcionamiento normal del sistema. 

🔹 Contenido generado por usuarios (apartados 6 y 7) 

Los usuarios que publiquen averías, respuestas técnicas o comentarios son responsables del contenido que comparten. Este contenido se encuentra bajo la licencia Creative Commons Atribución-NoComercial 4.0 Internacional (CC BY-NC 4.0), lo que permite su uso dentro de la plataforma siempre que no tenga fines comerciales y se respete la autoría. 

Por ejemplo, una respuesta técnica sobre un fallo de motor publicada por un usuario puede ser destacada en el feed de la comunidad o utilizada como referencia en futuras consultas, pero no puede ser vendida ni sublicenciada sin autorización. 

🔹 Protección frente a infracciones (apartado 13) 

MecaLink contempla medidas para proteger la integridad de la plataforma frente a infracciones de derechos de autor. En caso de que un usuario publique contenido técnico extraído de manuales, foros o fuentes externas sin atribución, se procederá a su eliminación y posible suspensión del usuario. 

🔹 Publicidad y contenido patrocinado (apartado 20.1) 

La plataforma permite la inclusión de anuncios técnicos o colaboraciones con talleres, marcas de repuestos o servicios automotrices. Estos contenidos deben estar claramente identificados como “Publicidad” o “Patrocinado”, y los usuarios que colaboren con marcas externas deberán etiquetar sus publicaciones con hashtags como #Publicidad o #ad. 

🔹 Uso de marcas registradas (apartado 28) 

Los usuarios pueden hacer referencia a marcas reconocidas (ej. Bosch®, Renault®, etc.) en sus publicaciones siempre que no infrinjan los derechos de propiedad de dichas marcas ni las utilicen con fines comerciales sin autorización. Por ejemplo, una publicación titulada “Problema con alternador Bosch®” es válida si se limita a una descripción técnica sin explotación comercial. 

2.8 

Análisis de riesgos 

En la siguiente tabla se presenta el análisis DAFO del proyecto MecaLink, donde se identifican las principales fortalezas, debilidades, oportunidades y amenazas que influyen en su desarrollo y posicionamiento. Este análisis permite entender mejor el contexto interno y externo del proyecto, ayudando a definir estrategias que potencien sus ventajas y mitiguen los riesgos, especialmente en un entorno digital competitivo y en constante evolución como el de las plataformas técnicas colaborativas. 

 

 

 

🔹 Debilidades 

Desarrollo individual del sistema: La ejecución del proyecto por una sola persona limita la velocidad de implementación y la capacidad de respuesta ante incidencias técnicas. 

Dependencia de contenido generado por usuarios: Existe el riesgo de que las respuestas técnicas no sean precisas si no se establece un sistema de moderación o validación. 

Ausencia de aplicación móvil nativa: La falta de versión móvil puede limitar el acceso desde dispositivos portátiles, especialmente en contextos de urgencia mecánica. 

Limitación de recursos económicos: El uso exclusivo de herramientas gratuitas puede restringir funcionalidades avanzadas o soporte técnico especializado. 

Fortalezas 

Arquitectura modular y escalable: El sistema está diseñado para crecer por módulos, permitiendo añadir nuevas funcionalidades sin comprometer la estabilidad. 

Integración con API VIN Analyzer: Mejora la calidad de los datos vehiculares y automatiza parte del proceso de publicación. 

Enfoque colaborativo técnico: Fomenta la participación de mecánicos, usuarios y expertos en la resolución de problemas reales. 

Documentación académica rigurosa: El proyecto cuenta con una base teórica sólida que respalda su estructura y funcionalidad. 

Diseño adaptado a necesidades reales: La plataforma responde a una problemática concreta en el sector automotriz, con potencial de impacto directo. 

 

Amenazas 

Competencia con foros técnicos consolidados: Plataformas como Foromecanicos o Reddit ya cuentan con comunidades activas y posicionamiento SEO. 

Riesgo de desinformación técnica: La publicación de soluciones incorrectas o peligrosas puede afectar la credibilidad del sistema si no se controla adecuadamente. 

Cambios en normativas de protección de datos: La evolución de leyes como el RGPD puede exigir ajustes en el tratamiento de información personal. 

Saturación de plataformas colaborativas: La proliferación de sistemas de ayuda técnica puede dificultar la diferenciación de MecaLink en el mercado. 

 

Oportunidades 

Digitalización del sector automotriz: Cada vez más talleres y usuarios buscan soluciones técnicas en línea, lo que abre espacio para plataformas especializadas. 

Colaboración con talleres y marcas de repuestos: Posibilidad de establecer alianzas para validar contenido y ofrecer servicios complementarios. 

Expansión a mercados emergentes: Países con alta demanda de soporte técnico vehicular pueden beneficiarse de una plataforma como MecaLink. 

Aplicación en formación profesional: El sistema puede ser utilizado como herramienta educativa en centros de formación mecánica o FP. 

 

 

 

3. Documento de análisis y diseño 

3.1 

Análisis y diseño de la arquitectura de la aplicación  

 

Figura . Arquitectura general de la aplicación MecaLink. Elaboración propia. 

Desde el inicio del proyecto, uno de los principales retos ha sido diseñar una arquitectura tecnológica que permitiera combinar funcionalidades clave como la publicación de averías, la integración automática de datos técnicos mediante número VIN, y la gestión colaborativa de respuestas, garantizando a su vez una experiencia fluida, segura y escalable. Para ello, la solución se estructura en tres capas bien diferenciadas: datos, lógica de negocio y presentación, donde cada componente cumple una función clara dentro de un sistema desacoplado y modular. 

La capa de persistencia de datos está compuesta por una base de datos relacional MySQL, que permite almacenar de forma estructurada la información de usuarios, averías, respuestas, votos y registros técnicos. Esta elección responde a la necesidad de realizar consultas eficientes, mantener integridad referencial y facilitar la exportación de datos para análisis posteriores. Paralelamente, se ha integrado la API ZPK VIN Analyzer, que permite enriquecer automáticamente los datos de los vehículos mediante el número VIN. Esta integración se realiza mediante peticiones HTTP seguras, y los datos recibidos se almacenan temporalmente para su visualización en la interfaz. 

La capa de lógica de negocio, desarrollada en PHP 8, adopta una arquitectura modular basada en el patrón MVC (Modelo-Vista-Controlador). El sistema se organiza en controladores específicos para cada funcionalidad (usuarios, averías, respuestas, administración), rutas personalizadas que definen los endpoints de la API REST, y modelos que gestionan la interacción con la base de datos. Esta estructura permite mantener el código limpio, reutilizable y fácilmente testeable. Además, el backend se encarga de validar los datos, gestionar la autenticación, controlar los permisos de acceso y coordinar la comunicación con la API externa. 

La capa de presentación, desarrollada con HTML, CSS y React, ofrece una interfaz dinámica, responsive y centrada en la experiencia del usuario. React ha sido elegido por su capacidad para construir componentes reutilizables, gestionar el estado de forma eficiente y facilitar la navegación entre vistas. La interfaz permite a los usuarios publicar averías, visualizar respuestas técnicas, votar soluciones y acceder a su perfil. También se ha diseñado un panel de administración para gestionar usuarios y contenidos, con funcionalidades específicas para moderadores. 

La comunicación entre el frontend y el backend se realiza mediante peticiones HTTP REST, utilizando el formato JSON para el intercambio de datos. Esta separación entre capas permite escalar el sistema, incorporar nuevas funcionalidades y facilitar la integración futura con aplicaciones móviles o servicios externos. 

Gracias a esta arquitectura, MecaLink no solo permite resolver problemas técnicos de forma colaborativa, sino también automatizar la entrada de datos, garantizar la seguridad de la información y ofrecer una experiencia moderna y profesional. Este enfoque basado en tecnologías desacopladas y especializadas ha facilitado el desarrollo y mantenimiento, y sienta las bases para escalar el sistema a más usuarios, talleres o funcionalidades en el futuro. 

3.1.1 

Capa de presentación con React 

Durante el desarrollo de MecaLink, se ha optado por utilizar React como tecnología principal para la capa de presentación. Esta decisión responde a la necesidad de construir una interfaz moderna, dinámica y altamente interactiva, capaz de adaptarse a distintos dispositivos y ofrecer una experiencia fluida al usuario. React, desarrollado por Meta, se ha consolidado como uno de los frameworks más robustos y versátiles para el desarrollo frontend, especialmente en proyectos que requieren modularidad, rendimiento y escalabilidad. 

Una de las principales ventajas de React es su enfoque basado en componentes reutilizables, lo que permite construir la interfaz como un conjunto de bloques independientes que pueden combinarse y adaptarse según el contexto. En MecaLink, se han desarrollado componentes específicos para la publicación de averías, visualización de respuestas, votación de soluciones, y gestión de perfiles. Esta estructura facilita el mantenimiento del código, la incorporación de nuevas funcionalidades y la personalización de la experiencia según el tipo de usuario (mecánico, conductor, administrador). 

Además, React incorpora un sistema eficiente de gestión del estado mediante herramientas como useState, useReducer o bibliotecas externas como Redux. En el caso de MecaLink, se ha utilizado useContext para compartir información entre componentes clave, como el estado de autenticación, los datos del usuario y las preferencias de visualización. Esta gestión centralizada del estado permite mantener la coherencia de la interfaz y responder rápidamente a cambios en tiempo real. 

La comunicación con el backend se realiza mediante peticiones HTTP REST, utilizando la biblioteca axios para enviar y recibir datos en formato JSON. Cada acción del usuario (como publicar una avería o votar una respuesta) genera una petición que se procesa en el servidor PHP, y la respuesta se refleja inmediatamente en la interfaz gracias al sistema de renderizado reactivo de React. 

A nivel de diseño visual, se ha optado por una interfaz responsive, adaptada a distintos tamaños de pantalla, desde ordenadores de escritorio hasta dispositivos móviles. Para ello, se han utilizado librerías como Tailwind CSS y componentes personalizados que permiten mantener una estética limpia, profesional y coherente con el enfoque técnico de la plataforma. 

React también ofrece herramientas que mejoran la productividad del desarrollo, como el sistema de Hot Reload, que permite visualizar cambios al instante sin necesidad de recargar manualmente la aplicación. Esta funcionalidad ha sido clave para acelerar el proceso de pruebas y ajustes durante los sprints de desarrollo. 

En términos arquitectónicos, la capa de presentación se ha estructurado siguiendo el patrón MVVM (Model-View-ViewModel), donde: 

El modelo representa la estructura de datos (usuarios, averías, respuestas). 

La vista se compone de componentes React que muestran la información y capturan la interacción del usuario. 

El ViewModel gestiona el estado, realiza llamadas al backend y actualiza la vista según los cambios recibidos. 

Gracias a esta arquitectura, la aplicación no solo permite una navegación fluida y una interacción intuitiva, sino que también está preparada para escalar en futuras versiones, incorporar nuevas vistas o integrarse con aplicaciones móviles mediante React Native si se desea. React se posiciona así como una solución robusta, moderna y eficiente para la capa de presentación de MecaLink. 

3.1.2 

Capa de lógica de negocio con PHP 

Se ha optado por la utilización de PHP 8 para la implementación de la capa de servicio (backend) de MecaLink. Esta decisión se fundamenta en las características técnicas y arquitectónicas que ofrece PHP, especialmente su modelo multiproceso, su integración nativa con bases de datos relacionales como MySQL, y su madurez como lenguaje ampliamente adoptado en el desarrollo web. PHP permite gestionar múltiples solicitudes simultáneas de forma eficiente, lo que resulta fundamental en una plataforma que debe manejar publicaciones, respuestas técnicas y consultas externas en tiempo real. 

 

La arquitectura del backend se ha estructurado siguiendo el patrón MVC (Modelo-Vista-Controlador), lo que facilita la separación de responsabilidades, la organización del código y su escalabilidad. Esta estructura modular permite dividir la lógica en carpetas específicas: 

Controller: Gestiona las solicitudes HTTP, valida los datos y coordina las respuestas. 

Model: Encapsula la lógica de acceso a la base de datos MySQL. 

Route: Define los endpoints disponibles para el frontend. 

Middleware : Puede incluir filtros de autenticación o validación adicional. 

Cada módulo está diseñado para cumplir una función clara dentro del flujo de procesamiento de las solicitudes, promoviendo un código limpio, desacoplado y fácilmente testeable. Por ejemplo, el controlador de averías se encarga de recibir la publicación de un fallo técnico, validar los campos, consultar la API VIN si se ha introducido un número válido, y almacenar el resultado en la base de datos. 

La comunicación entre el frontend (React) y el backend se realiza mediante peticiones HTTP REST, utilizando el formato JSON para el intercambio de datos. Esta arquitectura permite una integración fluida entre capas, facilita el desarrollo incremental y prepara el sistema para futuras extensiones como una API pública o una aplicación móvil. 

Además, PHP ofrece compatibilidad con herramientas modernas como JWT para la autenticación, validación de formularios, sanitización de entradas y protección contra ataques comunes como SQL Injection o Cross-Site Scripting (XSS). Estas medidas han sido implementadas para garantizar la seguridad del sistema y la integridad de los datos. 

En contraposición, no se ha optado por tecnologías como Node.js o frameworks como Spring Boot, ya que representan soluciones más orientadas a microservicios o entornos empresariales complejos. En el caso de MecaLink, PHP ofrece una solución más directa, ligera y adecuada para un desarrollo individual con recursos limitados, sin sacrificar la calidad ni la escalabilidad. 

Gracias a esta arquitectura, la capa de lógica de negocio de MecaLink permite gestionar de forma eficiente las operaciones críticas del sistema, mantener una estructura clara y modular, y garantizar una respuesta rápida y segura ante las solicitudes de los usuarios. 

3.1.2.1 

Diseño de API: REST API 

La arquitectura de MecaLink incluye una API RESTful que actúa como puente entre la capa de presentación (frontend) y la capa de lógica de negocio (backend). Esta API ha sido diseñada siguiendo los principios de la arquitectura REST (Representational State Transfer), lo que permite una comunicación eficiente, escalable y fácilmente integrable con otros sistemas o aplicaciones móviles en el futuro. 

Cada endpoint de la API responde a una acción específica del usuario, utilizando los métodos estándar del protocolo HTTP: 

GET: para recuperar información (ej. lista de averías). 

POST: para enviar nuevos datos (ej. publicar una avería). 

PUT: para actualizar registros existentes (ej. editar perfil). 

DELETE: para eliminar datos (ej. borrar una respuesta). 

La API está estructurada en rutas organizadas por funcionalidad, lo que facilita su mantenimiento y evolución. Por ejemplo: 

Endpoint 

Método 

Descripción 

/api/login 

POST 

Autenticación de usuario 

/api/register 

POST 

Registro de nuevo usuario 

/api/averias 

GET 

Listado de averías publicadas 

/api/averias 

POST 

Publicación de nueva avería 

/api/respuestas/{id} 

POST 

Envío de respuesta técnica a una avería 

/api/vin/{numero} 

GET 

Consulta de datos técnicos mediante VIN 

/api/admin/usuarios 

GET 

Gestión de usuarios desde el panel de administración 

Cada ruta está protegida mediante validaciones y, en los casos necesarios, autenticación por token (JWT). Esto garantiza que solo los usuarios autorizados puedan realizar acciones sensibles como publicar contenido o acceder a datos privados. 

La API responde en formato JSON, lo que facilita su consumo desde el frontend desarrollado en React. Además, se han implementado códigos de estado HTTP para indicar el resultado de cada operación: 

200 OK: operación exitosa. 

201 Created: recurso creado correctamente. 

400 Bad Request: error en los datos enviados. 

401 Unauthorized: acceso no autorizado. 

404 Not Found: recurso no encontrado. 

500 Internal Server Error: error del servidor. 

Esta estructura permite una comunicación clara, predecible y segura entre las distintas capas de la aplicación. Además, la documentación de la API se ha elaborado en paralelo al desarrollo, incluyendo ejemplos de uso, parámetros requeridos y respuestas esperadas, lo que facilita su mantenimiento y posible apertura futura como API pública. 

Gracias a este diseño, la API de MecaLink no solo permite gestionar el flujo de datos interno, sino que también prepara el sistema para futuras integraciones con aplicaciones móviles, sistemas de terceros o módulos de análisis técnico avanzado. 

3.1.3 

Integración con servicios y APIs externas (VIN, notificaciones) 

La plataforma MecaLink incorpora servicios externos que permiten ampliar sus funcionalidades y mejorar la experiencia del usuario. Estas integraciones se han realizado de forma modular, respetando los principios de desacoplamiento y escalabilidad, lo que facilita su mantenimiento y evolución futura. 

🔹 Integración con ZPK VIN Analyzer API 

Uno de los servicios clave integrados en el backend de MecaLink es la API ZPK VIN Analyzer, que permite obtener información técnica detallada de un vehículo a partir de su número VIN (Vehicle Identification Number). Esta funcionalidad resulta especialmente útil en el proceso de publicación de averías, ya que permite validar automáticamente los datos del vehículo y enriquecer la ficha técnica sin intervención manual. 

La comunicación con esta API se realiza mediante peticiones HTTP GET desde el backend PHP, y los datos recibidos se procesan antes de ser almacenados en la base de datos MySQL. Esta integración aporta: 

Mayor precisión en la descripción de vehículos. 

Reducción de errores humanos. 

Automatización del flujo de publicación. 

Mejora en la fiabilidad de los datos técnicos. 

🔹 Sistema de notificaciones internas 

Además de la integración con servicios externos, MecaLink incorpora un sistema de notificaciones internas que permite alertar a los usuarios sobre eventos relevantes dentro de la plataforma. Estas notificaciones se generan desde el backend y se almacenan en una tabla específica de la base de datos, permitiendo su visualización en tiempo real desde el panel de usuario. 

Entre los eventos que generan notificaciones se incluyen: 

Recepción de una respuesta técnica a una avería publicada. 

Validación o rechazo de una publicación por parte del administrador. 

Actualización de perfil o cambios en la configuración de cuenta. 

Alertas de nuevos artículos técnicos publicados. 

El sistema ha sido diseñado para ser extensible, permitiendo en el futuro la incorporación de notificaciones por correo electrónico o incluso push notifications si se desarrolla una versión móvil o se integra con servicios externos de mensajería. 

Gracias a estas integraciones, MecaLink no solo mejora la calidad y precisión de los datos técnicos, sino que también fortalece la comunicación interna entre usuarios, creando un entorno más dinámico, automatizado y centrado en la experiencia colaborativa. 

3.2 

Tecnologías/Herramientas usadas y descripción 

🧰 Tecnología / Herramienta 

📄 Descripción 

🐘 PHP 8 

Lenguaje de programación del lado del servidor utilizado para construir la lógica de negocio de MecaLink. Su modelo multiproceso y su integración nativa con MySQL permiten gestionar múltiples solicitudes simultáneas de forma eficiente. 

🗄️ MySQL 

Sistema de gestión de bases de datos relacional utilizado para almacenar toda la información de la plataforma, incluyendo usuarios, averías, respuestas y notificaciones. Su estructura relacional facilita consultas rápidas y seguras. 

⚛️ React 

Biblioteca de JavaScript para construir interfaces de usuario dinámicas y reactivas. Utilizada en el frontend de MecaLink para ofrecer una experiencia fluida y modular desde el navegador. 

🎨 Bootstrap 

Framework CSS que permite diseñar interfaces responsivas y estéticamente coherentes. Utilizado para estructurar el diseño visual del frontend con componentes reutilizables. 

🖼️ React Icons 

Librería que proporciona una amplia colección de iconos SVG integrables en componentes React. Utilizada para mejorar la experiencia visual y la navegación. 

🔍 ZPK VIN Analyzer API 

Servicio externo que permite obtener información técnica de vehículos a partir del número VIN. Integrado en el backend para enriquecer automáticamente las publicaciones de averías. 

📬 Postman 

Herramienta utilizada para probar y documentar los endpoints de la API REST. Permite enviar peticiones HTTP, verificar respuestas y organizar colecciones de pruebas. 

🧠 Axios 

Librería cliente HTTP basada en Promesas para JavaScript. Utilizada en el backend para realizar peticiones a servicios externos como la API VIN, y gestionar la comunicación entre frontend y backend. 

🧑‍💻 Visual Studio Code (VS Code) 

Editor de código multiplataforma utilizado durante el desarrollo. Ofrece funciones como resaltado de sintaxis, control de versiones, extensiones útiles y depuración integrada. 

🐙 GitHub 

Plataforma de control de versiones utilizada para alojar el repositorio del proyecto, gestionar ramas, registrar cambios y colaborar de forma estructurada. 

🎯 Canva 

Herramienta de diseño gráfico utilizada para crear elementos visuales del proyecto, como diagramas, banners y componentes de identidad visual. 

🎨 Figma 

Plataforma colaborativa de diseño utilizada para crear wireframes, prototipos interactivos y maquetas de alta fidelidad de la interfaz de usuario. 

🤖 ChatGPT 

Asistente de desarrollo utilizado para resolver dudas técnicas, redactar contenido académico y proponer mejoras en la estructura del código y del documento. 

Tabla 5. Herramientas utilizadas. Elaboración propia. 

 

 

 

3.3 Arquitectura de componentes 

La arquitectura de MecaLink se ha diseñado siguiendo un enfoque modular y escalable, que permite separar claramente las responsabilidades de cada capa del sistema. Esta división facilita el mantenimiento, la evolución del proyecto y la incorporación de nuevas funcionalidades sin afectar el núcleo de la aplicación. 

🔹 Capa de presentación (Frontend) 

La interfaz de usuario está desarrollada con React, una biblioteca de JavaScript que permite construir componentes reutilizables y dinámicos. Esta capa se encarga de: 

Mostrar los datos al usuario de forma clara y responsiva. 

Gestionar la navegación entre vistas (publicaciones, perfil, artículos, etc.). 

Interactuar con la API mediante peticiones HTTP (Axios). 

🔹 Capa de lógica de negocio (Backend) 

El backend está construido con PHP 8, y se encarga de procesar las solicitudes del frontend, aplicar las reglas de negocio y comunicarse con la base de datos. Entre sus responsabilidades destacan: 

Validación de datos y autenticación de usuarios. 

Gestión de publicaciones, respuestas, artículos y notificaciones. 

Integración con servicios externos como la API ZPK VIN Analyzer. 

🔹 Capa de persistencia (Base de datos) 

La base de datos utilizada es MySQL, organizada en tablas relacionales que almacenan la información estructurada del sistema. Esta capa permite: 

Consultas eficientes mediante SQL. 

Integridad referencial entre entidades (usuarios, problemas, soluciones). 

Inserción de datos iniciales mediante scripts Seed. 

🔹 Servicios externos 

MecaLink se conecta con servicios externos para enriquecer su funcionalidad: 

ZPK VIN Analyzer API para obtener datos técnicos de vehículos. 

Sistema de notificaciones internas para alertar a los usuarios sobre eventos relevantes. 

Esta arquitectura por componentes permite que cada módulo funcione de forma independiente pero coordinada, lo que facilita la escalabilidad, la reutilización de código y la implementación de mejoras futuras. 

3.3 

Arquitectura de componentes 

La arquitectura de MecaLink se ha estructurado en capas bien definidas que permiten mantener el sistema modular, escalable y fácil de mantener. Cada componente cumple una función específica dentro del flujo de la aplicación, lo que facilita la reutilización de código, la implementación de mejoras y la gestión de cambios sin afectar el núcleo del sistema. 

🧩 Frontend 

Componentes React reutilizables 

En MecaLink, la creación de componentes reutilizables en React es un pilar fundamental para mantener el código limpio y desacoplado. Estos componentes representan elementos visuales que se utilizan en múltiples partes de la interfaz, lo que evita duplicación de código y mejora la consistencia visual. 

Se han desarrollado componentes personalizados para elementos comunes como botones, tarjetas de averías, formularios y barras de búsqueda. Cada componente acepta props que permiten su personalización en distintos contextos, promoviendo así la reutilización sin perder flexibilidad. 

Algunos de los componentes más reutilizados son: 

AppButton: Botón configurable que permite definir estilo (primary, outline, text), ícono, tamaño y comportamiento. Se utiliza en formularios, navegación y acciones principales. 

ProblemCard: Tarjeta visual que muestra información resumida de una avería publicada. Incluye título, descripción, estado y botón de acción. 

SearchBar: Barra de búsqueda con campo de texto e ícono. Permite filtrar averías por palabra clave. 

NotificationItem: Componente que muestra una notificación con ícono, mensaje y fecha. Se utiliza en el panel de usuario. 

FormInput: Campo de texto reutilizable con validación, ícono y estilos personalizados. Usado en formularios de login, registro y publicación. 

Estos componentes se encuentran organizados en carpetas dentro de la capa de presentación, por encima de las páginas específicas. Gracias al enfoque componible de React, es posible construir interfaces completas a partir de estos elementos comunes. 

🔧 Backend 

Controladores modulares en PHP 

La lógica de negocio de MecaLink se ha implementado en PHP 8, utilizando una estructura modular basada en controladores. Cada controlador gestiona una entidad específica del sistema (usuarios, problemas, soluciones, artículos, notificaciones) y expone endpoints RESTful que permiten la interacción con el frontend. 

Los controladores están organizados en carpetas por funcionalidad, y cada uno incluye métodos para operaciones CRUD, validaciones, y respuestas estructuradas en formato JSON. Esta organización permite: 

Separación clara de responsabilidades. 

Reutilización de funciones comunes. 

Facilidad para realizar pruebas unitarias y mantenimiento. 

Además, se ha implementado un sistema de autenticación basado en tokens, que permite proteger rutas sensibles y gestionar sesiones de usuario de forma segura. 

🗄️ La persistencia de datos de MecaLink se gestiona mediante una base de datos relacional MySQL. El modelo se ha diseñado respetando rigurosamente las buenas prácticas de normalización (evitando redundancias), integridad referencial (mediante claves foráneas) y eficiencia en las consultas (mediante la definición de índices en campos clave). 

Gestión de la Conexión: La interacción con la base de datos se realiza desde el backend PHP (PDO), utilizando controladores específicos que encapsulan las operaciones CRUD (Create, Read, Update, Delete), garantizando una clara separación entre la lógica de negocio y la capa de persistencia. 

 

🌐 Servicios Externos 

Integración con APIs y Sistemas Auxiliares 

MecaLink se conecta con servicios externos fundamentales para enriquecer su funcionalidad técnica: 

ZPK VIN Analyzer API: Permite la obtención automatizada de datos técnicos de vehículos (marca, modelo, año, motor) a partir del Número de Identificación del Vehículo (VIN). Esto es crucial para la precisión de las consultas técnicas. 

Sistema de Notificaciones Internas: Genera alertas a los usuarios sobre eventos relevantes de la plataforma, como la recepción de respuestas, la validación de publicaciones o cambios de estado en sus consultas. 

Estas integraciones se gestionan en el backend y están encapsuladas en clases específicas que controlan las peticiones HTTP y el tratamiento de las respuestas. La comunicación entre el frontend (React) y la API REST del backend se realiza mediante Axios, implementando manejo de errores y validación de las cabeceras personalizadas (JWT). 

 

3.4 Modelado de Datos 

El modelado de datos de MecaLink se ha realizado siguiendo un enfoque relacional, utilizando MySQL. La estructura se ha diseñado para garantizar la integridad referencial y la escalabilidad del sistema. 

3.4.1 Base de datos relacional (MySQL) 

La base de datos está compuesta por un conjunto de tablas interrelacionadas mediante claves primarias y foráneas. Se han aplicado principios de normalización para minimizar la redundancia de datos. Además, se han definido índices en campos clave para optimizar las búsquedas y mejorar el rendimiento en operaciones de lectura. 

 

3.4.2 Tablas Principales 

Las tablas principales del sistema representan las entidades fundamentales de la plataforma. Las tablas clave son: usuarios, consultas, respuestas, articulos, vehiculos y notificaciones. 

Tabla 

Descripción 

usuarios 

Almacena la información de cuenta: nombre, correo electrónico,  

contraseña cifrada (password_hash), rol (usuario, mecanico, administrador) y estado. 

vehiculos 

Almacena los datos técnicos de los vehículos extraídos del VIN (marca, modelo, año, motor).  

Cada registro está vinculado a una o varias consultas. 

consultas 

Contiene las averías (los problemas) publicadas por los usuarios. Incluye título, descripción, 

 categoría, estado y una referencia a la tabla vehiculos. 

respuestas 

Guarda las soluciones técnicas aportadas por mecánicos. Está vinculada a una consulta  

específica y a un usuario. Contiene un campo booleano  

es_solucion para marcar la solución aceptada. 

articulos 

Almacena contenido técnico de expertos (guías o análisis).  

Soporta adjuntos (imágenes, PDF, vídeo) mediante campos url_imagen, url_pdf, url_video. 

notificaciones 

Registra los eventos internos (respuestas, validaciones) para alertar al usuario, 

 incluyendo mensaje, tipo, fecha y estado de lectura. 

 

3.4.3 Inserción de datos mediante Seed 

Para facilitar el desarrollo y las pruebas, se ha implementado un mecanismo de inserción de datos iniciales (Seed). Estos scripts insertan registros predefinidos en las tablas principales (usuarios de prueba con distintos roles, averías simuladas, respuestas técnicas, etc.), permitiendo la validación de los flujos de interacción y la detección de errores antes de la puesta en producción. 

3.5 Análisis y Diseño del Sistema Funcional 

El diseño funcional de MecaLink se ha basado en la identificación de los principales casos de uso y la división del sistema en módulos funcionales. 

🔹 Casos de Uso Principales 

 

🔹 Módulos Funcionales 

El sistema se ha dividido en módulos que agrupan operaciones relacionadas: 

Módulo de Autenticación: Registro, login y gestión de sesiones (basado en JWT). 

Módulo de Publicaciones (Consultas): Creación, edición, consulta y gestión de las averías publicadas. 

Módulo de Respuestas: Envío, edición y visualización de soluciones técnicas, y marcaje de la solución definitiva. 

Módulo de Artículos: Gestión de contenido técnico de expertos, incluyendo la subida de archivos adjuntos. 

Módulo de Notificaciones: Generación y visualización de alertas internas en tiempo real. 

Módulo de Administración: Panel de control para la moderación de contenido y la gestión de usuarios/roles. 

Esta arquitectura modular garantiza una estructura sólida, mantenible y escalable, donde cada capa funciona de forma independiente pero coordinada. 

 

 

 

 

3.6 

Análisis y diseño de la interfaz de usuario 

La interfaz de usuario de MecaLink ha sido diseñada con un enfoque centrado en la experiencia del usuario (UX), priorizando la claridad visual, la accesibilidad y la eficiencia en la navegación. El diseño se ha estructurado en capas componibles que permiten reutilizar elementos visuales y mantener una coherencia estética en toda la plataforma. 

🔹 Componentes visuales reutilizables en React 

La aplicación utiliza componentes React altamente reutilizables que permiten construir pantallas dinámicas y modulares. Estos componentes reciben props que permiten su personalización, lo que facilita su uso en distintos contextos sin duplicar código. 

Entre los componentes más utilizados destacan: 

AppButton: Botón configurable que permite definir estilo (primary, outline, text), ícono, tamaño y comportamiento. Se utiliza en formularios, navegación y acciones principales. 

ProblemCard: Tarjeta visual que muestra información resumida de una avería publicada. Incluye título, descripción, estado y botón de acción. 

SearchBar: Barra de búsqueda con campo de texto e ícono. Permite filtrar averías por palabra clave. 

NotificationItem: Componente que muestra una notificación con ícono, mensaje y fecha. Se utiliza en el panel de usuario. 

FormInput: Campo de texto reutilizable con validación, ícono y estilos personalizados. Usado en formularios de login, registro y publicación. 

Estos componentes se encuentran organizados en carpetas dentro de la capa de presentación, por encima de las páginas específicas. Gracias al enfoque componible de React, es posible construir interfaces completas a partir de estos elementos comunes. 

🔹 Estructura de navegación 

La navegación de MecaLink se ha diseñado para ser intuitiva y fluida. Se utiliza un sistema de rutas basado en React Router, que permite: 

Navegar entre vistas sin recargar la página. 

Mantener el estado de la sesión y los datos cargados. 

Aplicar protección de rutas según el rol del usuario (usuario, mecánico, administrador). 

Las vistas principales incluyen: 

Inicio: resumen de actividad reciente y acceso rápido a funciones clave. 

Publicaciones: listado de averías con filtros y búsqueda. 

Respuestas: panel para mecánicos con averías disponibles para responder. 

Artículos: sección de contenido técnico. 

Perfil: configuración de cuenta y notificaciones. 

🔹 Principios de diseño aplicados 

El diseño visual se ha basado en los siguientes principios: 

Consistencia: uso de estilos unificados mediante Bootstrap y React Icons. 

Jerarquía visual: uso de tamaños, colores y espaciado para guiar la atención del usuario. 

Accesibilidad: contraste adecuado, etiquetas claras y navegación compatible con teclado. 

Responsividad: adaptación automática a distintos tamaños de pantalla (desktop, tablet, móvil). 

Gracias a este diseño estructurado y modular, MecaLink ofrece una experiencia de usuario clara, eficiente y profesional, facilitando la interacción entre usuarios y expertos técnicos en un entorno colaborativo. 

3.7 

Wireframing y cardflow 

El proceso de diseño visual de MecaLink ha comenzado con la elaboración de wireframes y cardflows, herramientas fundamentales para definir la estructura de las pantallas, la jerarquía de información y los flujos de navegación antes de la implementación técnica. 

🔹 Wireframes funcionales 

Los wireframes se han desarrollado utilizando Figma, permitiendo una visualización clara de la interfaz sin distracciones estéticas. Cada pantalla ha sido representada en su estado inicial, mostrando: 

Distribución de componentes principales (botones, formularios, tarjetas). 

Jerarquía visual entre secciones (cabecera, contenido, pie de página). 

Espacios reservados para elementos dinámicos (notificaciones, resultados de búsqueda). 

Comportamiento esperado en pantallas responsivas. 

Este enfoque ha permitido validar la lógica de navegación y la disposición de elementos antes de aplicar estilos definitivos. 

🔹 Cardflow de navegación 

El cardflow representa el flujo de interacción entre pantallas, mostrando cómo el usuario se desplaza por la plataforma según sus acciones. Se ha estructurado en bloques conectados que reflejan: 

Inicio → Publicaciones → Detalle de avería → Responder 

Inicio → Artículos → Lectura → Comentario (futuro) 

Inicio → Perfil → Notificaciones → Configuración 

Inicio → Panel administrador → Validar publicaciones → Estadísticas 

Cada tarjeta del cardflow representa una vista funcional, con sus entradas, salidas y condiciones de navegación. Este modelo ha sido elaborado en Canva, facilitando su inclusión en los anexos del documento y su revisión colaborativa. 

🔹 Validación visual 

Antes de pasar al diseño de alta fidelidad, los wireframes y cardflows han sido revisados para asegurar: 

Coherencia entre módulos funcionales y pantallas. 

Fluidez en la navegación según el rol del usuario. 

Compatibilidad con los componentes React definidos previamente. 

Gracias a este proceso visual, se ha logrado una interfaz clara, funcional y alineada con los objetivos del sistema, reduciendo el riesgo de errores en la fase de implementación. 

3.8 

Identidad visual 

La identidad visual de MecaLink ha sido diseñada para transmitir profesionalismo, claridad y confianza, en línea con su propósito como plataforma técnica colaborativa. Se ha definido un conjunto de elementos gráficos que garantizan la coherencia estética en todas las pantallas y materiales del sistema. 

🔹 Paleta de colores 

La selección cromática se ha basado en tonos neutros y técnicos, que refuerzan la seriedad del entorno sin perder accesibilidad: 

Azul técnico (#1E3A8A): color principal, utilizado en botones, encabezados y elementos destacados. 

Gris claro (#F3F4F6): fondo de pantallas y tarjetas, aporta limpieza visual. 

Blanco (#FFFFFF): base neutra para formularios y componentes. 

Rojo suave (#EF4444): utilizado para alertas y mensajes de error. 

Verde éxito (#10B981): utilizado para confirmaciones y estados positivos. 

Esta paleta permite mantener un contraste adecuado y una jerarquía visual clara, facilitando la lectura y la navegación. 

🔹 Tipografía 

Se ha utilizado la fuente Inter, una tipografía moderna, legible y versátil, ideal para interfaces digitales. Sus variantes (Regular, Medium, Bold) permiten diferenciar títulos, subtítulos y contenido sin perder coherencia. 

Títulos: Inter Bold, tamaño 20–24px. 

Subtítulos: Inter Medium, tamaño 16–18px. 

Texto base: Inter Regular, tamaño 14–16px. 

La elección de esta tipografía contribuye a una experiencia visual clara y profesional. 

🔹 Iconografía 

La plataforma utiliza React Icons, una librería que proporciona íconos SVG escalables y estilizados. Se han seleccionado íconos simples y reconocibles para representar acciones comunes (editar, eliminar, responder, buscar), mejorando la usabilidad sin sobrecargar la interfaz. 

🔹 Logotipo y elementos gráficos 

El logotipo de MecaLink ha sido diseñado en Canva, combinando un símbolo técnico (engranaje) con una tipografía moderna. Este logotipo aparece en la cabecera de la plataforma, en el manual de usuario y en los documentos oficiales. 

Además, se han creado banners, tarjetas y elementos decorativos en Canva y Figma para reforzar la identidad visual en presentaciones y anexos. 

Gracias a esta identidad visual definida, MecaLink ofrece una experiencia coherente, profesional y accesible, alineada con su propósito funcional y su público objetivo. 

3.9 

Prototipos de alta fidelidad 

Tras la validación de los wireframes y cardflows, se ha procedido a la creación de prototipos de alta fidelidad utilizando la herramienta Figma, con el objetivo de representar visualmente la interfaz definitiva de MecaLink antes de su implementación técnica. 

🔹 Objetivos del prototipo 

El prototipo de alta fidelidad permite: 

Visualizar el diseño final con estilos, colores, tipografía e iconografía aplicados. 

Simular la navegación entre pantallas mediante enlaces interactivos. 

Validar la experiencia de usuario (UX) en condiciones cercanas al producto real. 

Facilitar la comunicación entre diseño y desarrollo, reduciendo ambigüedades. 

🔹 Pantallas prototipadas 

Se han diseñado las siguientes vistas clave: 

Pantalla de inicio: resumen de actividad reciente, acceso rápido a publicaciones y artículos. 

Listado de averías: tarjetas con filtros, búsqueda y navegación hacia el detalle. 

Detalle de avería: información completa, respuestas recibidas, botón para responder. 

Panel de respuestas: vista para mecánicos con averías disponibles y estado de cada una. 

Artículos técnicos: listado de contenido publicado por expertos, con vista de lectura. 

Perfil de usuario: configuración de cuenta, notificaciones y datos personales. 

Panel de administración: validación de publicaciones, gestión de usuarios y estadísticas. 

Cada pantalla ha sido diseñada respetando la identidad visual definida previamente, utilizando componentes reutilizables y estilos consistentes. 

🔹 Interactividad y validación 

El prototipo incluye enlaces interactivos entre pantallas, simulando el comportamiento real de la plataforma. Esto ha permitido: 

Validar la lógica de navegación. 

Detectar posibles redundancias o pasos innecesarios. 

Recoger feedback antes de la implementación técnica. 

Además, se ha utilizado el modo presentación de Figma para realizar pruebas internas y mostrar el flujo completo del sistema a colaboradores y revisores académicos. 

Gracias a estos prototipos, MecaLink ha podido consolidar su diseño visual y funcional antes de la fase de desarrollo, asegurando una implementación más precisa, coherente y centrada en el usuario. 

3.10 

Librerías UI (Bootstrap, React Icons) 

Para garantizar una interfaz coherente, responsiva y visualmente atractiva, MecaLink incorpora dos librerías clave en su capa de presentación: Bootstrap y React Icons. Estas herramientas permiten acelerar el desarrollo visual, mantener una estética uniforme y mejorar la experiencia del usuario. 

🔹 Bootstrap 

Bootstrap es un framework CSS ampliamente utilizado para construir interfaces web responsivas y estructuradas. En MecaLink, se ha utilizado para: 

Definir la estructura de las pantallas mediante un sistema de rejilla (grid system). 

Aplicar estilos consistentes a botones, formularios, tarjetas y alertas. 

Garantizar la compatibilidad con distintos tamaños de pantalla (desktop, tablet, móvil). 

Reducir el tiempo de desarrollo visual mediante componentes predefinidos. 

Gracias a Bootstrap, la interfaz mantiene una estética profesional y una organización clara, facilitando la navegación y la interacción del usuario. 

🔹 React Icons 

React Icons es una librería que proporciona una colección extensa de íconos SVG integrables directamente en componentes React. Su uso en MecaLink permite: 

Representar acciones comunes mediante íconos intuitivos (editar, eliminar, responder, buscar). 

Mejorar la accesibilidad visual y la comprensión de la interfaz. 

Mantener una estética ligera y escalable, sin necesidad de cargar paquetes externos pesados. 

Los íconos se han integrado en botones, tarjetas, formularios y encabezados, reforzando la identidad visual definida en el apartado anterior. 

Estas librerías UI han sido seleccionadas por su compatibilidad con React, su flexibilidad y su capacidad para mantener una interfaz limpia, moderna y funcional. Su uso contribuye directamente a la calidad visual y técnica de la plataforma. 

 

4 — Documento de implementación e implantación del sistema 

4.1 

Implementación 

La implementación de MecaLink se ha llevado a cabo siguiendo una arquitectura modular y escalable, que permite separar claramente las responsabilidades entre el frontend, el backend, la base de datos y los servicios externos. Esta división ha facilitado el desarrollo progresivo del sistema, permitiendo validar cada componente de forma independiente antes de su integración final. 

🔹 Backend (PHP 8) 

El backend se ha desarrollado en PHP 8, utilizando una estructura basada en controladores modulares. Cada controlador gestiona una entidad específica del sistema (usuarios, problemas, respuestas, artículos, notificaciones) y expone endpoints RESTful que permiten la comunicación con el frontend. 

Se ha implementado un sistema de autenticación mediante tokens. 

Las rutas están organizadas por funcionalidad y protegidas según el rol del usuario. 

Se han definido respuestas JSON estructuradas para facilitar el consumo desde React. 

🔹 Frontend (React) 

La interfaz de usuario se ha construido con React, utilizando componentes reutilizables y un sistema de rutas dinámico. Cada vista (inicio, publicaciones, perfil, artículos) se compone de elementos visuales independientes que permiten: 

Navegación fluida sin recarga de página. 

Interacción directa con la API mediante Axios. 

Adaptación responsiva a distintos dispositivos. 

🔹 Base de datos (MySQL) 

La base de datos relacional se ha implementado en MySQL, con tablas normalizadas y relaciones bien definidas. Se han creado scripts de migración y seed para facilitar la creación del esquema y la inserción de datos de prueba. 

Tablas principales: usuarios, problemas, respuestas, artículos, notificaciones. 

Claves foráneas para mantener la integridad referencial. 

Índices en campos clave para optimizar las consultas. 

🔹 Integración con servicios externos 

MecaLink se conecta con la API ZPK VIN Analyzer para enriquecer automáticamente las publicaciones de averías mediante el número VIN. Esta integración se ha encapsulado en una clase específica que gestiona: 

Peticiones HTTP con Axios. 

Inclusión automática del token de autenticación. 

Validación de respuestas y manejo de errores. 

🔹 Control de versiones 

Todo el código fuente se ha gestionado mediante Git y alojado en GitHub, utilizando ramas para separar el desarrollo de nuevas funcionalidades, correcciones de errores y despliegues. Esta estrategia ha permitido mantener un historial claro y facilitar la colaboración. 

4.2 

Instalación y configuración 

La instalación y configuración del entorno de desarrollo de MecaLink se ha realizado de forma local, utilizando herramientas ampliamente adoptadas en el desarrollo web moderno. Este proceso ha permitido establecer una base sólida para el desarrollo, las pruebas y el despliegue del sistema. 

🔹 Entorno local 

El entorno de desarrollo se ha configurado en un equipo local con los siguientes componentes: 

Herramienta 

Descripción 

PHP 8 

Lenguaje de programación utilizado para el backend. Instalado mediante XAMPP para facilitar la ejecución local. 

MySQL 

Sistema de gestión de bases de datos relacional. Configurado como servicio local con acceso desde PHPMyAdmin. 

Visual Studio Code (VS Code) 

Editor de código principal, con extensiones para PHP, React, Git y control de versiones. 

Node.js + npm 

Utilizados para gestionar dependencias del frontend React y ejecutar scripts de desarrollo. 

Postman 

Herramienta para probar y documentar los endpoints de la API REST. 

Git + GitHub 

Control de versiones y alojamiento del repositorio del proyecto. 

Figma + Canva 

Herramientas de diseño utilizadas para wireframes, prototipos y elementos visuales. 

🔹 Estructura de carpetas 

El proyecto se ha organizado en carpetas separadas para cada componente: 

/backend: contiene los controladores PHP, rutas, configuración de base de datos y lógica de negocio. 

/frontend: contiene los componentes React, vistas, estilos y configuración de rutas. 

/docs: incluye documentación técnica, diagramas y recursos visuales. 

🔹 Variables de entorno 

Se han definido variables de entorno para separar la configuración sensible del código fuente: 

DB_HOST, DB_USER, DB_PASS, DB_NAME: parámetros de conexión a la base de datos. 

API_KEY_VIN: clave de acceso a la API externa ZPK VIN Analyzer. 

TOKEN_SECRET: clave para la generación y validación de tokens JWT. 

Estas variables se almacenan en archivos .env y se cargan automáticamente al iniciar el servidor. 

🔹 Configuración inicial 

Antes de iniciar el desarrollo, se han realizado las siguientes acciones: 

Creación de la base de datos y ejecución de scripts de migración. 

Configuración de rutas en el backend y pruebas con Postman. 

Instalación de dependencias del frontend (npm install) y configuración de React Router. 

Definición de estilos base con Bootstrap y React Icons. 

Gracias a esta configuración estructurada, el entorno de desarrollo de MecaLink ha permitido una implementación eficiente, segura y organizada, facilitando la colaboración y el mantenimiento del sistema. 

 

4.4 

Manual de usuario 

El manual de usuario de MecaLink tiene como objetivo guiar a los distintos perfiles de usuarios en el uso correcto de la plataforma. Se han definido tres roles principales: usuario registrado, mecánico y administrador, cada uno con funcionalidades específicas y accesos diferenciados. 

🔹 Usuario registrado 

El usuario registrado puede acceder a las siguientes funcionalidades: 

Publicar avería: desde el panel principal, el usuario puede crear una nueva publicación describiendo el problema de su vehículo. Debe completar campos como título, descripción, categoría y número VIN. 

Consultar respuestas: en la sección de “Mis averías”, el usuario puede ver las soluciones técnicas aportadas por mecánicos, ordenadas por fecha y estado. 

Recibir notificaciones: el sistema genera alertas automáticas cuando se recibe una respuesta, se valida una publicación o se actualiza el estado de una avería. 

Leer artículos técnicos: el usuario puede acceder a contenido especializado publicado por expertos, organizado por categorías. 

🔹 Mecánico 

El mecánico tiene acceso a funcionalidades orientadas a la colaboración técnica: 

Consultar averías disponibles: desde el panel de mecánico, puede ver las averías publicadas por usuarios que aún no han sido resueltas. 

Proponer soluciones: puede redactar una respuesta técnica detallada, adjuntar enlaces o referencias, y enviarla para revisión. 

Historial de respuestas: puede consultar las soluciones que ha enviado, su estado (pendiente, validada, rechazada) y los comentarios del administrador. 

Acceso a artículos: puede leer y comentar contenido técnico para mantenerse actualizado. 

🔹 Administrador 

El administrador gestiona el contenido y supervisa la actividad de la plataforma: 

Validar publicaciones: puede revisar averías y respuestas antes de que se publiquen, asegurando la calidad del contenido. 

Gestionar usuarios: tiene acceso a un panel para activar, desactivar o modificar roles de cuenta. 

Supervisar artículos: puede aprobar o rechazar contenido técnico propuesto por expertos. 

Generar estadísticas: puede visualizar métricas de uso, número de publicaciones, respuestas activas y usuarios registrados. 

Cada usuario accede a la plataforma mediante credenciales personales. La navegación se realiza desde un menú lateral que adapta sus opciones según el rol. Las acciones están acompañadas de mensajes de confirmación, validación de formularios y alertas visuales para mejorar la experiencia. 

5.1 

Resultados obtenidos y conclusiones 

El desarrollo de MecaLink ha permitido alcanzar los objetivos definidos en la fase inicial del proyecto, consolidando una plataforma funcional, modular y orientada a la colaboración técnica en el ámbito de las averías mecánicas. A lo largo del proceso, se han obtenido resultados significativos tanto a nivel técnico como organizativo. 

🔹 Resultados técnicos 

Se ha implementado un backend en PHP con controladores modulares, rutas protegidas y respuestas estructuradas. 

Se ha desarrollado una interfaz en React con componentes reutilizables, navegación fluida y diseño responsivo. 

La base de datos relacional en MySQL ha sido normalizada y optimizada para consultas eficientes. 

Se ha integrado una API externa (ZPK VIN Analyzer) para enriquecer las publicaciones mediante el número VIN. 

Se han realizado pruebas funcionales y técnicas que validan el comportamiento del sistema en distintos escenarios. 

🔹 Resultados organizativos 

Se ha documentado el proceso completo mediante diagramas, wireframes, prototipos y manuales de usuario. 

Se ha mantenido una estructura de trabajo disciplinada, respetando la planificación y adaptando el flujo según las necesidades reales. 

Se ha utilizado control de versiones con Git y GitHub, lo que ha permitido un seguimiento claro del progreso. 

🔹 Conclusiones 

MecaLink demuestra ser una solución viable y escalable para la gestión colaborativa de averías técnicas. Su arquitectura modular, su interfaz clara y su integración con servicios externos permiten una experiencia de usuario eficiente y profesional. El proyecto ha servido como ejercicio completo de análisis, diseño, implementación y validación de un sistema web funcional, aplicando buenas prácticas de desarrollo y documentación académica. 

5.2 

Diario de bitácora 

El diario de bitácora recoge las principales actividades realizadas durante el desarrollo del proyecto MecaLink, organizadas cronológicamente. Este registro permite visualizar el progreso técnico, los ajustes realizados y las decisiones tomadas en cada etapa. 

Fecha 

Actividad realizada 

Comentario técnico o académico 

01/09/2025 

Inicio del proyecto y definición del alcance 

Se establecieron los objetivos funcionales y técnicos del sistema. 

03/09/2025 

Diseño inicial de la base de datos relacional 

Se definieron las entidades principales y sus relaciones. 

06/09/2025 

Implementación de controladores PHP y estructura modular del backend 

Se creó la arquitectura RESTful con rutas protegidas. 

10/09/2025 

Desarrollo de componentes React reutilizables 

Se diseñaron elementos visuales como tarjetas, botones y formularios. 

13/09/2025 

Integración de la API externa ZPK VIN Analyzer 

Se encapsuló la lógica de conexión y validación de respuestas. 

17/09/2025 

Pruebas de endpoints con Postman 

Se validaron respuestas, errores y protección por roles. 

20/09/2025 

Diseño de wireframes y cardflow en Figma y Canva 

Se definió la estructura visual y los flujos de navegación. 

23/09/2025 

Creación de prototipos de alta fidelidad 

Se aplicaron estilos, tipografía y navegación interactiva. 

26/09/2025 

Redacción del manual de usuario 

Se documentaron las funcionalidades por rol de usuario. 

29/09/2025 

Revisión académica del capítulo 3 y ajustes de estilo 

Se adaptó el contenido a los criterios de redacción formal. 

02/10/2025 

Pruebas funcionales completas 

Se simularon escenarios reales de uso para validar el sistema. 

05/10/2025 

Preparación de anexos visuales y diagramas 

Se exportaron figuras desde Figma y Canva para documentación final. 

08/10/2025 

Revisión final del sistema y preparación para despliegue 

Se verificó la coherencia entre módulos, interfaz y base de datos. 

 

 

5.3 

Temporalización y desviación sobre la planificación inicial 

La planificación inicial del proyecto MecaLink se estructuró en fases semanales, con objetivos definidos para cada etapa. A lo largo del desarrollo, se han producido ajustes necesarios para adaptarse a la carga de trabajo real, la validación técnica y la documentación académica. 

🔹 Comparativa entre planificación inicial y ejecución real 

Fase 

Actividad prevista 

Fecha planificada 

Fecha real 

Desviación 

Comentario 

1 

Definición del proyecto y análisis funcional 

01/09/2025 

01/09/2025 

0 días 

Inicio puntual según cronograma. 

2 

Diseño de base de datos y estructura backend 

03/09/2025 

03/09/2025 

0 días 

Diseño completado sin retrasos. 

3 

Desarrollo de frontend y componentes React 

06/09/2025 

07/09/2025 

+1 día 

Ajuste menor por pruebas visuales. 

4 

Integración de API externa y pruebas 

10/09/2025 

13/09/2025 

+3 días 

Retraso por validación de respuestas y manejo de errores. 

5 

Diseño visual (wireframes, prototipos) 

15/09/2025 

17/09/2025 

+2 días 

Se amplió el tiempo para mejorar la coherencia visual. 

6 

Redacción del capítulo 3 y anexos 

20/09/2025 

22/09/2025 

+2 días 

Ajustes por revisión académica. 

7 

Pruebas funcionales y validación 

25/09/2025 

26/09/2025 

+1 día 

Validación completa sin impacto crítico. 

8 

Redacción del capítulo 4 y cierre 

28/09/2025 

30/09/2025 

+2 días 

Se amplió el tiempo para documentación detallada. 

🔹 Análisis de desviaciones 

Las desviaciones observadas han sido mínimas y justificadas por la necesidad de mejorar la calidad técnica y documental del proyecto. No se han producido retrasos acumulativos significativos, y el calendario general se ha respetado en más del 90 % de las fases. 

El enfoque flexible y disciplinado ha permitido adaptar la temporalización sin comprometer los entregables ni la coherencia del sistema. 

 

Bibliografía 

Bootstrap. (2025). Documentación oficial. Disponible en: https://getbootstrap.com 

React. (2025). Documentación oficial. Disponible en: https://react.dev 

PHP. (2025). Manual oficial de PHP 8. Disponible en: https://www.php.net/manual/es/ 

MySQL. (2025). Guía de referencia técnica. Disponible en: https://dev.mysql.com/doc/ 

Postman. (2025). Documentación de pruebas de API. Disponible en: https://learning.postman.com 

GitHub Docs. (2025). Control de versiones y colaboración. Disponible en: https://docs.github.com 

ZPK VIN Analyzer API. (2025). Documentación técnica de la API. Disponible en: https://vin.zpkapi.com/docs 

Figma. (2025). Diseño de interfaces y prototipos. Disponible en: https://www.figma.com 

Canva. (2025). Herramienta de diseño visual. Disponible en: https://www.canva.com 

PlantUML. (2025). Generación de diagramas UML. Disponible en: https://plantuml.com/es/ 

MDN Web Docs. (2025). Referencia técnica para HTML, CSS y JavaScript. Disponible en: https://developer.mozilla.org 

 

 

 

 

 

 

 

 