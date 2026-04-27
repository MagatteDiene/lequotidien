<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create('fr_FR');
        $editeur = User::where('role', 'editeur')->first();
        $categories = Categorie::all();

        $titres_par_categorie = [
            'Politique' => [
                'Débat houleux à l\'Assemblée sur la nouvelle loi',
                'Élections municipales : les premiers sondages tombent',
                'Visite officielle du Chef de l\'État en Europe',
                'Réforme des retraites : ce qui change demain',
                'Crise diplomatique : les enjeux cachés',
                'Le ministre annonce une nouvelle aide pour les jeunes',
            ],
            'Technologie' => [
                'Le nouveau smartphone pliable révolutionne le marché',
                'Intelligence Artificielle : faut-il en avoir peur ?',
                'La 6G arrive plus tôt que prévu : les détails',
                'Télétravail : les meilleurs outils de 2026',
                'Cybersécurité : une attaque massive déjouée',
                'Exploration spatiale : la Lune comme base arrière',
            ],
            'Sport' => [
                'Victoire historique de l\'équipe nationale',
                'Transfert record en football : les coulisses',
                'Tennis : le tournoi de Grand Chelem approche',
                'Cyclisme : le parcours du prochain tour dévoilé',
                'Formule 1 : duel au sommet pour le titre mondial',
                'JO 2028 : la préparation des athlètes français',
            ],
            'Économie' => [
                'Croissance en hausse : les chiffres encourageants',
                'Inflation : comment protéger votre épargne',
                'La bourse en plein essor après les annonces de la BCE',
                'Immobilier : est-ce le moment d\'acheter ?',
                'Startup : une licorne française lève 100 millions',
                'Marché de l\'emploi : les secteurs qui recrutent',
            ],
            'Culture' => [
                'Le dernier film de SF bat tous les records',
                'Exposition immersive : l\'art rencontre la tech',
                'Littérature : le prix Goncourt crée la surprise',
                'Musique : le festival d\'été annonce sa programmation',
                'Séries : la saison finale que tout le monde attend',
                'Théâtre : une pièce engagée qui fait vibrer Paris',
            ],
            'Santé' => [
                'Nouveau traitement contre la migraine : un espoir',
                'Sommeil : les secrets pour une nuit réparatrice',
                'Alimentation : les bienfaits du régime méditerranéen',
                'Santé mentale : l\'importance de la déconnexion',
                'Sport et santé : les meilleurs exercices après 40 ans',
                'Vaccins : les dernières avancées de la recherche',
            ],
        ];

        $contenus_par_categorie = [
            'Politique' => "L'Assemblée nationale a été le théâtre d'échanges particulièrement vifs aujourd'hui. Les députés de l'opposition ont vivement critiqué la nouvelle proposition de loi, dénonçant un manque de concertation avec les partenaires sociaux.\n\nDe son côté, la majorité défend un texte jugé nécessaire pour moderniser l'économie et faire face aux défis futurs. Le ministre a souligné que des amendements constructifs pourraient être discutés dans les jours à venir, mais que l'ossature du projet resterait intacte.\n\nLes citoyens, quant à eux, semblent partagés. Les récents sondages montrent une fracture claire dans l'opinion publique, avec une légère avance pour les opposants à la réforme. Les prochaines semaines s'annoncent décisives pour le gouvernement, qui jouera une grande partie de sa crédibilité sur ce dossier épineux.",
            'Technologie' => "L'innovation technologique franchit un nouveau cap avec les récentes annonces de la Silicon Valley. Les avancées en matière d'Intelligence Artificielle générative redéfinissent complètement notre rapport aux outils numériques au quotidien.\n\nPlusieurs experts s'accordent à dire que cette décennie sera marquée par l'automatisation intelligente. Cependant, des voix s'élèvent pour alerter sur les risques liés à la vie privée et à la sécurité des données personnelles. Les régulateurs européens préparent d'ailleurs une nouvelle batterie de lois pour encadrer ces pratiques.\n\nEn parallèle, le secteur des semi-conducteurs connaît une tension sans précédent, poussant les constructeurs à revoir leurs chaînes d'approvisionnement pour éviter de futures pénuries. L'avenir s'annonce aussi passionnant que complexe.",
            'Sport' => "L'ambiance était électrique dans le stade ce soir, alors que les deux équipes rivales s'affrontaient pour le titre tant convoité. Dès les premières minutes, l'intensité physique était palpable, chaque joueur donnant le meilleur de lui-même.\n\nC'est finalement un but spectaculaire inscrit à la 89ème minute qui a scellé le sort de la rencontre. L'entraîneur, ému, a salué la résilience et l'esprit d'équipe de ses joueurs. « Nous avons travaillé dur toute l'année pour vivre ce moment », a-t-il déclaré en conférence de presse.\n\nLes supporters ont célébré cette victoire historique tard dans la nuit. Ce match restera sans aucun doute gravé dans les annales comme l'une des finales les plus palpitantes de la décennie.",
            'Économie' => "Les marchés financiers ont clôturé en forte hausse aujourd'hui, portés par des indicateurs économiques plus rassurants que prévu. L'inflation semble enfin montrer des signes de ralentissement, offrant une bouffée d'oxygène aux consommateurs et aux entreprises.\n\nLa Banque Centrale a confirmé qu'elle maintiendrait ses taux directeurs stables pour le trimestre en cours, écartant ainsi les craintes d'un resserrement monétaire brutal. Dans ce contexte, les secteurs de l'industrie et de la technologie tirent particulièrement leur épingle du jeu en bourse.\n\nMalgré cette embellie, les analystes restent prudents. Les tensions géopolitiques mondiales continuent de faire peser une incertitude sur les coûts de l'énergie et des matières premières, obligeant les investisseurs à diversifier leurs portefeuilles.",
            'Culture' => "Le dernier festival international du film a une fois de plus tenu toutes ses promesses, récompensant des œuvres audacieuses et profondément humaines. Le grand prix a été décerné à un réalisateur indépendant, créant la surprise générale face aux superproductions.\n\nDu côté des expositions, la nouvelle rétrospective consacrée à l'art contemporain au musée de la ville attire des foules record. L'installation immersive, mêlant réalité virtuelle et sculptures classiques, propose une réflexion fascinante sur notre époque numérisée.\n\nL'industrie littéraire n'est pas en reste, avec une rentrée marquée par la publication de plusieurs romans très attendus. Les critiques saluent une année exceptionnelle, prouvant que la créativité reste le meilleur antidote face à la morosité ambiante.",
            'Santé' => "Une nouvelle étude scientifique majeure vient de mettre en lumière les bienfaits sous-estimés d'une alimentation équilibrée sur la santé mentale. Les chercheurs ont démontré un lien direct entre la consommation de certains nutriments et la réduction des symptômes liés au stress et à l'anxiété.\n\nParallèlement, les autorités sanitaires lancent une vaste campagne de prévention concernant l'importance du sommeil. Avec l'omniprésence des écrans, les troubles nocturnes touchent une part de plus en plus large de la population, impactant directement la productivité et le bien-être général.\n\nEnfin, l'innovation médicale continue ses avancées spectaculaires avec les premiers essais prometteurs d'un traitement ciblé contre certaines maladies chroniques. Ces progrès ouvrent de nouvelles perspectives pour des millions de patients à travers le monde."
        ];

        // On génère 50 articles
        for ($i = 0; $i < 50; $i++) {
            $categorie = $categories->random();
            $nom_cat = $categorie->nom;
            
            // On pioche un titre spécifique ou on en génère un générique
            $titre_base = $faker->randomElement($titres_par_categorie[$nom_cat] ?? ['Actualité importante']);
            $titre = $titre_base . ' (' . ($i + 1) . ')'; // On ajoute l'index pour l'unicité du slug
            
            // On prend le contenu réaliste selon la catégorie
            $contenu_realiste = $contenus_par_categorie[$nom_cat] ?? "Contenu non disponible.";

            Article::create([
                'titre' => $titre,
                'slug' => Str::slug($titre),
                'resume' => Str::limit($contenu_realiste, 120),
                'contenu' => $contenu_realiste,
                'image' => null, // Pas d'images par défaut
                'publie' => $faker->boolean(90), // 90% de chances d'être publié
                'categorie_id' => $categorie->id,
                'user_id' => $editeur->id,
                'created_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
            ]);
        }
    }
}
