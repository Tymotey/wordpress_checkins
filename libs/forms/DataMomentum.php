<?php

namespace BTDEV_INSCRIERI\Forms;

use BTDEV_INSCRIERI\Forms\DefaultData as DEFAULT_DATA;

class DataMomentum extends DEFAULT_DATA
{
	public function __construct()
	{
		$this->name = 'momentum';
		$this->full_data = $this->default_data;
		$this->full_data = array_replace_recursive($this->full_data, [
			'mode' => 'test', // 'live', 'test'

			'tandc' => [
				'enabled' => false,
				'text' => 'Sunt de acord ca datele personale introduse să fie folosite în scopul organizării evenimentului și pentru anunțurile legate de acest eveniment sau următoarele evenimente.'
			],
			'captcha' => [
				'enabled' => false
			],
			'payment' => [
				'with' => ['stripe', 'ordinPlata'],
				'enabled' => true,
				'currency' => 'RON',
				'base_price' => '15000',
				'payment_for' => 'ConferintaMomentum',
				'description' => 'Inscriere Conferinta Momentum'
			],
			'links' => [
				'url' => '/inregistrare',
				'success' => '/inscriere-cu-succes',
				'cancel' => '/inscriere-esuata',
				'edit' => '/editeaza-inregistrare',
				'view' => '/vizualizeaza-inregistrare',
			],

			'table' => 'btdev_entries_momentum',

			'emails' => [
				'description' => 'Conferinta Momentum',
				'list' => [
					'saved' => [
						'enabled' => false,
						'content' => 'Salut!<br /><br />Îți mulțumim că ai completat formularul. Ai fost redirecționat la formularul de plată pentru a termina înscrierea la conferință.<br />Dacă ai închis fereasta poți da click <a href="%payment_link%">aici</a>(valabil 12h) pentru a reîncerca plata și termina înscrierea.<br />În cazul în care v-ați răzgândit dumneavoastră să participați, vă rugăm să nu luați în considerare acest mesaj.<br /><br />Iti multumim si te asteptam sa termini inscrierea.',
						'subject' => 'Completare date',
					],
					'done' => [
						'enabled' => true,
						'content' => 'Înscrierea la conferința MOMENTUM din data de 8-10 Februarie 2024 a fost realizată cu succes. <br /><br />Atelierele pentru care ai/ați optat sunt cele de mai jos:<br />%summary%<br /><br />Vă așteptăm cu acest e-mail în data de 8 Februarie 2024 de la ora 16:00 pentru check-in la Campusul Bisericii Baptiste Speranța din Oradea.',
						'subject' => 'Te-ai/V-ați înscris cu succes!',
					],
					'not_done' => [
						'enabled' => true,
						'content' => 'Ne pare rău să vă informăm că înscrierea dumneavoastră la această conferință nu a fost finalizată cu succes. Plata nu a putut fi înregistrată!<br />Dacă dorești să revii asupra plății, accesează următorul <a href="%payment_link%">LINK</a> pentru a continua plata (valabil 12h).<br /><br />În cazul în care v-ați răzgândit dumneavoastră să participați, vă rugăm să nu luați în considerare acest mesaj.<br /><br />Dacă ați întâmpinat probleme de natură tehnică în procesul de înscriere, vă rugăm să ne scrieți la adresa de e-mail: <a href="mailto:amin@bbso.ro">amin@bbso.ro</a>',
						'subject' => 'Înregistrare eșuată!',
					],
					'edit' => [
						'enabled' => true,
						'content' => 'Ai editat înscrierea la conferința MOMENTUM din data de 8-10 Februarie 2024.<br /><br />Atelierele pentru care ai/ați optat sunt cele de mai jos:<br />%summary%',
						'subject' => 'S-a editat înregistrarea',
					]
				]
			],

			'repeater_fields' => [
				'firstname' => [
					'type' => 'text',
					'title' => 'Prenume',
					'name' => 'firstname',
					'requiredddddddd' => true,
					'attr_html' => [
						'minlength' => 3
					],
					'input_class_add' => ['firstname_entry']
				],
				'lastname' => [
					'type' => 'text',
					'title' => 'Nume (Familie)',
					'name' => 'lastname',
					'requiredddddddd' => true,
					'input_class_add' => ['lastname_entry']
				],
				'oras' => [
					'type' => 'text',
					'title' => 'Oras',
					'name' => 'oras',
					'width' => 'third',
					'requiredddddddd' => true,
				],
				'data_nasterii' => [
					'type' => 'date',
					'title' => 'Data nasterii',
					'name' => 'data_nasterii',
					'width' => 'third',
					'requiredddddddd' => true,
				],
				'sex' => [
					'type' => 'select',
					'title' => 'Sex',
					'name' => 'sex',
					'width' => 'third',
					'options' => [
						'M' => ['title' => 'Masculin'],
						'F' => ['title' => 'Feminin']
					],
					'requiredddddddd' => true,
				],
				'email' => [
					'type' => 'text',
					'title' => 'Email',
					'name' => 'email',
					'requiredddddddd' => true,
					'price' => ['value' => -1000, 'type' => 'relative']
				],
				'telefon' => [
					'type' => 'text',
					'title' => 'Telefon',
					'name' => 'telefon',
					'placeholder' => '+40744111222',
					'requiredddddddd' => true,
					'attr_html' => [
						'minlength' => 9
					],
				],
				'biserica' => [
					'type' => 'text',
					'title' => 'Biserica',
					'name' => 'biserica',
					'requiredddddddd' => true,
				],
				'domeniu' => [
					'type' => 'select',
					'title' => 'Implicare',
					'name' => 'domeniu',
					'options' => [
						"" => ['title' => 'Alege domeniu'],
						"pastor" => [
							'title' => 'Pastor',
							'show_in_form' => true,
							'price' => ['value' => -1000, 'type' => 'relative']
						],
						"predicator" => ['title' => 'Predicator', 'disabled' => true],
						"inchinare" => ['title' => 'Închinare'],
						"tineri" => ['title' => 'Tineri'],
						"copii" => ['title' => 'Copii'],
						"adolescenti" => ['title' => 'Adolescenți'],
						"grupuri_mici" => ['title' => 'Grupuri Mici'],
						"administrativ" => ['title' => 'Administrativ'],
						"bun_venit" => ['title' => 'Bun venit'],
						"media" => ['title' => 'Dept. Media'],
						"altele" => ['title' => 'Altele...'],
					],
					'requiredddddddd' => true,
				],
				'html_atelier' => [
					'type' => 'html',
					'name' => 'html_atelier',
					'html' => '<h6 class="black_text normal_case">Selectează atelierele</h6>
						<p class="under_title">
							Schimbarea ulterioară a atelierelor nu este posibilă.
							<a href="/ateliere/" target="__blank">
								Vezi aici detalii despre ateliere.
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.625 20C5.15833 20 4.771 19.846 4.463 19.538C4.15433 19.2293 4 18.8417 4 18.375V5.625C4 5.15833 4.15433 4.771 4.463 4.463C4.771 4.15433 5.15833 4 5.625 4H10.725C10.875 4 10.996 4.04567 11.088 4.137C11.1793 4.229 11.225 4.35 11.225 4.5C11.225 4.65 11.1793 4.77067 11.088 4.862C10.996 4.954 10.875 5 10.725 5H5.625C5.45833 5 5.31267 5.06267 5.188 5.188C5.06267 5.31267 5 5.45833 5 5.625V18.375C5 18.5417 5.06267 18.6873 5.188 18.812C5.31267 18.9373 5.45833 19 5.625 19H18.375C18.5417 19 18.6873 18.9373 18.812 18.812C18.9373 18.6873 19 18.5417 19 18.375V13.275C19 13.125 19.046 13.004 19.138 12.912C19.2293 12.8207 19.35 12.775 19.5 12.775C19.65 12.775 19.771 12.8207 19.863 12.912C19.9543 13.004 20 13.125 20 13.275V18.375C20 18.8417 19.846 19.2293 19.538 19.538C19.2293 19.846 18.8417 20 18.375 20H5.625ZM9.375 14.625C9.275 14.5083 9.225 14.3873 9.225 14.262C9.225 14.1373 9.275 14.0167 9.375 13.9L18.3 5H14.5C14.35 5 14.229 4.954 14.137 4.862C14.0457 4.77067 14 4.65 14 4.5C14 4.35 14.0457 4.229 14.137 4.137C14.229 4.04567 14.35 4 14.5 4H19.2C19.4333 4 19.625 4.075 19.775 4.225C19.925 4.375 20 4.56667 20 4.8V9.5C20 9.65 19.9543 9.771 19.863 9.863C19.771 9.95433 19.65 10 19.5 10C19.35 10 19.2293 9.95433 19.138 9.863C19.046 9.771 19 9.65 19 9.5V5.7L10.075 14.65C9.975 14.7333 9.86267 14.775 9.738 14.775C9.61267 14.775 9.49167 14.725 9.375 14.625Z" fill="#171718"></path>
								</svg>
							</a>
						</p>',
					'width' => 'full'
				],
				'atelier_a' => [
					'type' => 'radio',
					'title' => 'Atelier 1',
					'name' => 'atelier_a',
					'label_add' => 'Vineri(09.02.2024 10:45-12:00)',
					'options' => [
						'nu_particip' => ['title' => 'Nu particip', 'short_id' => 'nu'],
						'atelier_razvan' => ['title' => 'Rolurile celor care coordonează lucrarea de închinare a bisericii', 'vorbitor' => 'Răzvan Rește', 'short_id' => 'at_raz', 'price' => ['value' => -5000, 'type' => 'relative']],
						'atelier_cipri' => ['title' => '„Măsoară pulsul” lucrării și al comunității tale.', 'vorbitor' => 'Ciprian Gag', 'short_id' => 'at_cipri', 'price' => ['value' => 5000, 'type' => 'relative']],
						'atelier_sido_cecilia' => ['title' => 'Kidz connect: conectarea generației aici și acum.', 'vorbitor' => 'Sidonia Gag & Cecilia Coita', 'short_id' => 'at_sido'],
						'atelier_marcel' => ['title' => 'Grupurile mici și ADN-ul bisericii tale', 'vorbitor' => 'Marcel Niculaș', 'short_id' => 'at_mar'],
						'atelier_romica' => ['title' => 'Cum să revitalizezi o biserică până nu-i prea târziu?', 'vorbitor' => 'Romică Iuga', 'short_id' => 'at_rom', 'price' => ['value' => 5000, 'type' => 'absolute']],
						'atelier_anda' => ['title' => 'Epidemia de burnout și impactul său asupra bisericii locale din România', 'vorbitor' => 'Anda Mogoș', 'short_id' => 'at_and'],
						'atelier_doroteea' => ['title' => 'Frica și anxietatea - cum le gestionăm credincioși fiind?', 'vorbitor' => 'Doroteea Purel', 'short_id' => 'at_dor'],
						'atelier_samuel' => ['title' => 'Abuzul spiritual - o provocare pentru biserica contemporană', 'vorbitor' => 'Samuel Tuțac', 'short_id' => 'at_sam'],
						'atelier_vlad' => ['title' => 'Între curiozitate, frământare, îndoială și necredință – întrebările tânărului din perspectivă apologetică', 'vorbitor' => 'Vlad Crîznic', 'short_id' => 'at_vla'],
						'atelier_amiel_radu' => ['title' => 'Parteneriatele inter-bisericești în perioada apostolică: de la Pavel la Didahia(c. 50–90 d.hr.)', 'vorbitor' => 'Amiel Drimbe & Radu Gheorghiță', 'short_id' => 'at_ami'],
						'atelier_teofil' => ['title' => 'Te iert, dar nu te uit', 'vorbitor' => 'Teofil Cotrău', 'short_id' => 'at_teo'],
						'atelier_media' => ['title' => 'Rolul departamentului media în creșterea bisericii', 'vorbitor' => 'MEDIA BBSO', 'short_id' => 'at_med'],
					],
					'disable_other' => 'atelier_ar',
					'width' => 'full',
					'requiredddddddd' => true,
					'wrapper_class_add' => ['atelier_wrapper']
				],
				'atelier_ar' => [
					'type' => 'radio',
					'title' => 'Atelier 2',
					'name' => 'atelier_ar',
					'label_add' => 'Vineri(09.02.2024 12:15-13:30)',
					'options' => [
						'nu_particip' => ['title' => 'Nu particip', 'short_id' => 'nu'],
						'atelier_razvan' => ['title' => 'Rolurile celor care coordonează lucrarea de închinare a bisericii', 'vorbitor' => 'Răzvan Rește', 'short_id' => 'at_raz', 'price' => ['value' => -5000, 'type' => 'relative']],
						'atelier_cipri' => ['title' => '„Măsoară pulsul” lucrării și al comunității tale.', 'vorbitor' => 'Ciprian Gag', 'short_id' => 'at_cipri', 'price' => ['value' => 5000, 'type' => 'relative']],
						'atelier_sido_cecilia' => ['title' => 'Kidz connect: conectarea generației aici și acum.', 'vorbitor' => 'Sidonia Gag & Cecilia Coita', 'short_id' => 'at_sido'],
						'atelier_marcel' => ['title' => 'Grupurile mici și ADN-ul bisericii tale', 'vorbitor' => 'Marcel Niculaș', 'short_id' => 'at_mar', 'price' => ['value' => -5000, 'type' => 'relative']],
						'atelier_romica' => ['title' => 'Cum să revitalizezi o biserică până nu-i prea târziu?', 'vorbitor' => 'Romică Iuga', 'short_id' => 'at_rom'],
						'atelier_anda' => ['title' => 'Epidemia de burnout și impactul său asupra bisericii locale din România', 'vorbitor' => 'Anda Mogoș', 'short_id' => 'at_and'],
						'atelier_doroteea' => ['title' => 'Frica și anxietatea - cum le gestionăm credincioși fiind?', 'vorbitor' => 'Doroteea Purel', 'short_id' => 'at_dor'],
						'atelier_samuel' => ['title' => 'Abuzul spiritual - o provocare pentru biserica contemporană', 'vorbitor' => 'Samuel Tuțac', 'short_id' => 'at_sam'],
						'atelier_vlad' => ['title' => 'Între curiozitate, frământare, îndoială și necredință – întrebările tânărului din perspectivă apologetică', 'vorbitor' => 'Vlad Crîznic', 'short_id' => 'at_vla', 'disabled' => false],
						'atelier_amiel_radu' => ['title' => 'Parteneriatele inter-bisericești în perioada apostolică: de la Pavel la Didahia(c. 50–90 d.hr.)', 'vorbitor' => 'Amiel Drimbe & Radu Gheorghiță', 'short_id' => 'at_ami', 'show_in_form' => false],
						'atelier_teofil' => ['title' => 'Te iert, dar nu te uit', 'vorbitor' => 'Teofil Cotrău', 'short_id' => 'at_teo'],
						'atelier_media' => ['title' => 'Rolul departamentului media în creșterea bisericii', 'vorbitor' => 'MEDIA BBSO', 'short_id' => 'at_med'],
					],
					'disable_other' => 'atelier_a',
					'width' => 'full',
					'requiredddddddd' => true,
					'wrapper_class_add' => ['atelier_wrapper']
				],
				'total_row' => [
					'type' => 'total_row',
					'title' => '',
					'name' => 'total_row',
					'label_add' => 'Vineri(09.02.2024 12:15-13:30)',
					'width' => 'full',
				],
			],

			'tables' => [
				'entries_public' => [
					'fields' => [
						'atelier_a' => [
							'order' => 40,
							'title' => 'Atelier 1',
							'table' => 'entry'
						],
						'atelier_ar' => [
							'order' => 50,
							'title' => 'Atelier 2',
							'table' => 'entry'
						]
					]
				],
			],
		]);
	}
}
