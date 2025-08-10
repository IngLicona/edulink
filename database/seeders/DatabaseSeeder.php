<?php

namespace Database\Seeders;

use App\Models\configuracion;
use App\Models\Estudiante;
use App\Models\Formacion;
use App\Models\Gestion;
use App\Models\Grado;
use App\Models\Materia;
use App\Models\Nivel;
use App\Models\Paralelo;
use App\Models\Periodo;
use App\Models\Personal;
use App\Models\Ppff;
use App\Models\Turno;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        User::create([
            "name"=> "Oscar Licona",
            "email"=> "admin@admin.com",
            "password" => Hash::make("12345678")
        ])->assignRole("ADMINISTRADOR");

        configuracion::create([
            "nombre"=> "Universidad Politecnica del Centro",
            "descripcion"=> "updc",
            "direccion"=> "Tumbulushal, villa el cielo 86190",
            "telefono"=> "9991255342",
            "divisa"=> "MX$",
            "correo_electronico"=> "difusion@updc.edu.mx",
            "web"=> "www.updc.com",
            "logo"=> "uploads/logos/1751168008_upc.jpeg",
        ]);

        Gestion::create(["nombre" => "2024"]);
        Gestion::create(["nombre" => "2025"]);
        Gestion::create(["nombre" => "2026"]);

        Periodo::create(["nombre" => "1er Trimestre", "gestion_id" => 1]);
        Periodo::create(["nombre" => "2do Trimestre", "gestion_id" => 1]);
        Periodo::create(["nombre" => "3er Trimestre", "gestion_id" => 1]);

        Periodo::create(["nombre" => "1er Trimestre", "gestion_id" => 2]);
        Periodo::create(["nombre" => "2do Trimestre", "gestion_id" => 2]);
        Periodo::create(["nombre" => "3er Trimestre", "gestion_id" => 2]);

        Periodo::create(["nombre" => "1er Trimestre", "gestion_id" => 3]);
        Periodo::create(["nombre" => "2do Trimestre", "gestion_id" => 3]);
        Periodo::create(["nombre" => "3er Trimestre", "gestion_id" => 3]);

        Nivel::create(['nombre' => 'KINDER']);
        Nivel::create(['nombre' => 'PRIMARIA']);
        Nivel::create(['nombre' => 'SECUNDARIA']);

        Grado::create(['nombre' => '1ro', 'nivel_id' => 1]);
        Grado::create(['nombre' => '2do', 'nivel_id' => 1]);
        Grado::create(['nombre' => '3ro', 'nivel_id' => 1]);
        Grado::create(['nombre' => '1ro', 'nivel_id' => 2]);
        Grado::create(['nombre' => '2do', 'nivel_id' => 2]);
        Grado::create(['nombre' => '3ro', 'nivel_id' => 2]);
        Grado::create(['nombre' => '4to', 'nivel_id' => 2]);
        Grado::create(['nombre' => '5to', 'nivel_id' => 2]);
        Grado::create(['nombre' => '6to', 'nivel_id' => 2]);
        Grado::create(['nombre' => '1ro', 'nivel_id' => 3]);
        Grado::create(['nombre' => '2do', 'nivel_id' => 3]);
        Grado::create(['nombre' => '3ro', 'nivel_id' => 3]);

        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 1]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 2]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 3]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 4]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 5]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 6]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 7]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 8]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 9]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 10]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 11]);
        Paralelo::create(['nombre'=> 'A', 'grado_id'=> 12]);

        Turno::create(['nombre' => 'Matutino']);
        Turno::create(['nombre' => 'Vespertino']);
        
        Materia::create(['nombre' => 'Matematicas']);
        Materia::create(['nombre' => 'Formacion Civica y Etica']);
        Materia::create(['nombre' => 'Biologia']);
        Materia::create(['nombre' => 'Español']);

        // PERSONAL ADMINISTRATIVO
        $usuario1 = User::create([
            'name' => 'Juan Mendoza Gomez', 
            'email' => 'juan.mendoza@escuela.com', 
            'password' => Hash::make('87654321')
        ]);
        $usuario1->assignRole('DIRECTOR/A GENERAL');

        $personal1 = Personal::create([
            'usuario_id' => $usuario1->id,
            'tipo' => 'administrativo',
            'nombre' => 'Juan',
            'paterno' => 'Mendoza',
            'materno' => 'Gomez',
            'ci' => '87654321',
            'fecha_nacimiento' => '1985-05-15',
            'direccion' => 'Av. Libertad 123',
            'telefono' => '8218912012',
            'profesion' =>'Lic. en Administración',
            'foto' => null
        ]);

        $usuario2 = User::create([
            'name' => 'María Rodriguez López', 
            'email' => 'maria.rodriguez@escuela.com', 
            'password' => Hash::make('11223344')
        ]);
        $usuario2->assignRole('SECRETARIO/A');

        $personal2 = Personal::create([
            'usuario_id' => $usuario2->id,
            'tipo' => 'administrativo',
            'nombre' => 'María',
            'paterno' => 'Rodriguez',
            'materno' => 'López',
            'ci' => '11223344',
            'fecha_nacimiento' => '1990-08-20',
            'direccion' => 'Calle Principal 456',
            'telefono' => '9991234567',
            'profesion' =>'Lic. en Contabilidad',
            'foto' => null
        ]);

        // PERSONAL DOCENTE
        $usuario3 = User::create([
            'name' => 'Carlos Fernández Silva', 
            'email' => 'carlos.fernandez@escuela.com', 
            'password' => Hash::make('55667788')
        ]);
        $usuario3->assignRole('DOCENTE');

        $personal3 = Personal::create([
            'usuario_id' => $usuario3->id,
            'tipo' => 'docente',
            'nombre' => 'Carlos',
            'paterno' => 'Fernández',
            'materno' => 'Silva',
            'ci' => '55667788',
            'fecha_nacimiento' => '1982-03-10',
            'direccion' => 'Av. Educación 789',
            'telefono' => '9998765432',
            'profesion' =>'Lic. en Matemáticas',
            'foto' => null
        ]);

        $usuario4 = User::create([
            'name' => 'Ana Martínez Pérez', 
            'email' => 'ana.martinez@escuela.com', 
            'password' => Hash::make('99887766')
        ]);
        $usuario4->assignRole('DOCENTE');

        $personal4 = Personal::create([
            'usuario_id' => $usuario4->id,
            'tipo' => 'docente',
            'nombre' => 'Ana',
            'paterno' => 'Martínez',
            'materno' => 'Pérez',
            'ci' => '99887766',
            'fecha_nacimiento' => '1988-11-25',
            'direccion' => 'Calle Conocimiento 321',
            'telefono' => '9993334455',
            'profesion' =>'Lic. en Español y Literatura',
            'foto' => null
        ]);

        $usuario5 = User::create([
            'name' => 'Roberto García Herrera', 
            'email' => 'roberto.garcia@escuela.com', 
            'password' => Hash::make('44556677')
        ]);
        $usuario5->assignRole('DOCENTE');

        $personal5 = Personal::create([
            'usuario_id' => $usuario5->id,
            'tipo' => 'docente',
            'nombre' => 'Roberto',
            'paterno' => 'García',
            'materno' => 'Herrera',
            'ci' => '44556677',
            'fecha_nacimiento' => '1980-07-14',
            'direccion' => 'Av. Ciencia 654',
            'telefono' => '9997778888',
            'profesion' =>'Lic. en Biología',
            'foto' => null
        ]);

        // FORMACIONES DE EJEMPLO
        
        // Formaciones para Juan Mendoza (Director)
        Formacion::create([
            'personal_id' => $personal1->id,
            'titulo' => 'Licenciatura en Administración de Empresas',
            'institucion' => 'Universidad Nacional Autónoma',
            'nivel' => 'Licenciatura',
            'fecha_graduacion' => '2007-12-15',
            'archivo' => null
        ]);

        Formacion::create([
            'personal_id' => $personal1->id,
            'titulo' => 'Maestría en Gestión Educativa',
            'institucion' => 'Instituto de Estudios Superiores',
            'nivel' => 'Maestría',
            'fecha_graduacion' => '2015-06-20',
            'archivo' => null
        ]);

        // Formaciones para María Rodriguez (Secretaria)
        Formacion::create([
            'personal_id' => $personal2->id,
            'titulo' => 'Bachillerato en Ciencias Sociales',
            'institucion' => 'Colegio Nacional',
            'nivel' => 'Bachillerato',
            'fecha_graduacion' => '2008-07-10',
            'archivo' => null
        ]);

        Formacion::create([
            'personal_id' => $personal2->id,
            'titulo' => 'Licenciatura en Contabilidad y Finanzas',
            'institucion' => 'Universidad Tecnológica',
            'nivel' => 'Licenciatura',
            'fecha_graduacion' => '2012-11-25',
            'archivo' => null
        ]);

        // Formaciones para Carlos Fernández (Docente de Matemáticas)
        Formacion::create([
            'personal_id' => $personal3->id,
            'titulo' => 'Bachillerato en Ciencias Exactas',
            'institucion' => 'Instituto Técnico Superior',
            'nivel' => 'Bachillerato',
            'fecha_graduacion' => '2000-06-30',
            'archivo' => null
        ]);

        Formacion::create([
            'personal_id' => $personal3->id,
            'titulo' => 'Licenciatura en Matemáticas',
            'institucion' => 'Universidad de Ciencias',
            'nivel' => 'Licenciatura',
            'fecha_graduacion' => '2004-12-18',
            'archivo' => null
        ]);

        Formacion::create([
            'personal_id' => $personal3->id,
            'titulo' => 'Especialidad en Didáctica de las Matemáticas',
            'institucion' => 'Centro de Perfeccionamiento Docente',
            'nivel' => 'Especialidad',
            'fecha_graduacion' => '2010-03-15',
            'archivo' => null
        ]);

        // Formaciones para Ana Martínez (Docente de Español)
        Formacion::create([
            'personal_id' => $personal4->id,
            'titulo' => 'Licenciatura en Lengua y Literatura Española',
            'institucion' => 'Universidad de Humanidades',
            'nivel' => 'Licenciatura',
            'fecha_graduacion' => '2010-07-22',
            'archivo' => null
        ]);

        Formacion::create([
            'personal_id' => $personal4->id,
            'titulo' => 'Maestría en Literatura Hispanoamericana',
            'institucion' => 'Universidad de Estudios Avanzados',
            'nivel' => 'Maestría',
            'fecha_graduacion' => '2016-12-10',
            'archivo' => null
        ]);

        // Formaciones para Roberto García (Docente de Biología)
        Formacion::create([
            'personal_id' => $personal5->id,
            'titulo' => 'Licenciatura en Biología',
            'institucion' => 'Universidad de Ciencias Naturales',
            'nivel' => 'Licenciatura',
            'fecha_graduacion' => '2002-08-30',
            'archivo' => null
        ]);

        Formacion::create([
            'personal_id' => $personal5->id,
            'titulo' => 'Especialidad en Ecología y Medio Ambiente',
            'institucion' => 'Instituto de Investigación Ambiental',
            'nivel' => 'Especialidad',
            'fecha_graduacion' => '2008-05-15',
            'archivo' => null
        ]);

        // EJEMPLO DE PPFFS PARA TESTING
        $ppff1 = Ppff::create([
            'nombre' => 'Carmen',
            'paterno' => 'López',
            'materno' => 'Méndez',
            'ci' => '12345678',
            'fecha_nacimiento' => '1985-05-15',
            'telefono' => '9991234567',
            'parentesco' => 'madre',
            'ocupacion' => 'Enfermera',
            'direccion' => 'Calle Libertad #123, Col. Centro'
        ]);

        $ppff2 = Ppff::create([
            'nombre' => 'José',
            'paterno' => 'Hernández',
            'materno' => 'Silva',
            'ci' => '87654321',
            'fecha_nacimiento' => '1980-08-20',
            'telefono' => '9998765432',
            'parentesco' => 'padre',
            'ocupacion' => 'Ingeniero',
            'direccion' => 'Av. Reforma #456, Col. Moderna'
        ]);

        $ppff3 = Ppff::create([
            'nombre' => 'María',
            'paterno' => 'González',
            'materno' => 'Torres',
            'ci' => '11223344',
            'fecha_nacimiento' => '1975-12-10',
            'telefono' => '9995556677',
            'parentesco' => 'abuela',
            'ocupacion' => 'Comerciante',
            'direccion' => 'Calle Principal #789, Col. Popular'
        ]);

        // EJEMPLO DE ESTUDIANTES USANDO LOS PPFFS CREADOS
        $usuarioEst1 = User::create([
            'name' => 'Ana Sofia López Hernández',
            'email' => '12345001_' . time() . '@estudiante.escuela.com',
            'password' => Hash::make('12345001')
        ]);
        $usuarioEst1->assignRole('ESTUDIANTE');

        Estudiante::create([
            'usuario_id' => $usuarioEst1->id,
            'ppffs_id' => $ppff1->id,
            'nombre' => 'Ana Sofia',
            'paterno' => 'López',
            'materno' => 'Hernández',
            'ci' => '12345001',
            'fecha_nacimiento' => '2010-03-15',
            'genero' => 'femenino',
            'telefono' => '9991111222',
            'direccion' => 'Calle Libertad #123, Col. Centro',
            'foto' => null,
            'estado' => 'activo'
        ]);

        $usuarioEst2 = User::create([
            'name' => 'Carlos Miguel Hernández González',
            'email' => '12345002_' . time() . '@estudiante.escuela.com',
            'password' => Hash::make('12345002')
        ]);
        $usuarioEst2->assignRole('ESTUDIANTE');

        Estudiante::create([
            'usuario_id' => $usuarioEst2->id,
            'ppffs_id' => $ppff2->id,
            'nombre' => 'Carlos Miguel',
            'paterno' => 'Hernández',
            'materno' => 'González',
            'ci' => '12345002',
            'fecha_nacimiento' => '2012-07-22',
            'genero' => 'masculino',
            'telefono' => '9992223344',
            'direccion' => 'Av. Reforma #456, Col. Moderna',
            'foto' => null,
            'estado' => 'activo'
        ]);

                // PPFF adicionales
        $ppff4 = Ppff::create([
            'nombre' => 'Luis',
            'paterno' => 'Ramírez',
            'materno' => 'Ortiz',
            'ci' => '33445566',
            'fecha_nacimiento' => '1982-01-12',
            'telefono' => '9996667788',
            'parentesco' => 'padre',
            'ocupacion' => 'Carpintero',
            'direccion' => 'Calle Roble #45, Col. Las Palmas'
        ]);

        $ppff5 = Ppff::create([
            'nombre' => 'Patricia',
            'paterno' => 'Morales',
            'materno' => 'Vega',
            'ci' => '77889900',
            'fecha_nacimiento' => '1986-04-18',
            'telefono' => '9994445566',
            'parentesco' => 'madre',
            'ocupacion' => 'Maestra',
            'direccion' => 'Av. Sol #789, Col. Jardines'
        ]);

        $ppff6 = Ppff::create([
            'nombre' => 'Héctor',
            'paterno' => 'Santos',
            'materno' => 'Cruz',
            'ci' => '99112233',
            'fecha_nacimiento' => '1979-09-30',
            'telefono' => '9998889900',
            'parentesco' => 'tío',
            'ocupacion' => 'Chofer',
            'direccion' => 'Calle Luna #120, Col. Estrellas'
        ]);

        // Estudiantes adicionales
        $usuarioEst3 = User::create([
            'name' => 'Fernanda Ramírez Morales',
            'email' => '12345003_' . time() . '@estudiante.escuela.com',
            'password' => Hash::make('12345003')
        ]);
        $usuarioEst3->assignRole('ESTUDIANTE');

        Estudiante::create([
            'usuario_id' => $usuarioEst3->id,
            'ppffs_id' => $ppff4->id,
            'nombre' => 'Fernanda',
            'paterno' => 'Ramírez',
            'materno' => 'Morales',
            'ci' => '12345003',
            'fecha_nacimiento' => '2011-05-08',
            'genero' => 'femenino',
            'telefono' => '9993332211',
            'direccion' => 'Calle Roble #45, Col. Las Palmas',
            'foto' => null,
            'estado' => 'activo'
        ]);

        $usuarioEst4 = User::create([
            'name' => 'Diego Santos Vega',
            'email' => '12345004_' . time() . '@estudiante.escuela.com',
            'password' => Hash::make('12345004')
        ]);
        $usuarioEst4->assignRole('ESTUDIANTE');

        Estudiante::create([
            'usuario_id' => $usuarioEst4->id,
            'ppffs_id' => $ppff5->id,
            'nombre' => 'Diego',
            'paterno' => 'Santos',
            'materno' => 'Vega',
            'ci' => '12345004',
            'fecha_nacimiento' => '2013-10-14',
            'genero' => 'masculino',
            'telefono' => '9995554433',
            'direccion' => 'Av. Sol #789, Col. Jardines',
            'foto' => null,
            'estado' => 'activo'
        ]);

        $usuarioEst5 = User::create([
            'name' => 'Valeria Cruz Ortiz',
            'email' => '12345005_' . time() . '@estudiante.escuela.com',
            'password' => Hash::make('12345005')
        ]);
        $usuarioEst5->assignRole('ESTUDIANTE');

        Estudiante::create([
            'usuario_id' => $usuarioEst5->id,
            'ppffs_id' => $ppff6->id,
            'nombre' => 'Valeria',
            'paterno' => 'Cruz',
            'materno' => 'Ortiz',
            'ci' => '12345005',
            'fecha_nacimiento' => '2014-01-25',
            'genero' => 'femenino',
            'telefono' => '9997776655',
            'direccion' => 'Calle Luna #120, Col. Estrellas',
            'foto' => null,
            'estado' => 'activo'
        ]);

    }
}