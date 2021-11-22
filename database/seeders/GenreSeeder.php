<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [];
        for ($i=0; $i < 1000; $i++) { 
            $arr = [
                ...$arr, 
                [
                    'name' => Str::random(10)
                ]
            ];
        }

        DB::table('genres')->insert($arr);

        DB::table('genres')->insert(
        [
            [
                'name' => 'Drama',
                'enabled' => true,
                'description' => 'A drama movie is a movie that depends that depends mostly on in-depth character development, interaction, and highly emotional themes. In a good drama film, the audience are able to experience'
            ],
            [
                'name' => 'Romance',
                'enabled' => true,
                'description' => 'Romance films or romance movies are romantic love stories recorded in visual media for broadcast in theaters and on TV that focus on passion, emotion, and the affectionate romantic involvement of the main characters and the journey that their love takes them through dating, courtship or marriage.'
            ],
            [
                'name' => 'Animation',
                'enabled' => true,
                'description' => 'Animation is a method in which figures are manipulated to appear as moving images. In traditional animation, images are drawn or painted by hand on transparent celluloid sheets to be photographed and exhibited on film.'
            ],
            [
                'name' => 'Fantasy',
                'enabled' => true,
                'description' => 'Fantasy films are films that belong to the fantasy genre with fantastic themes, usually magic, supernatural events, mythology, folklore, or exotic fantasy worlds'
            ],
            [
                'name' => 'Anime',
                'enabled' => true,
                'description' => 'A comedy film is a category of film in which the main emphasis is on humor. These films are designed to make the audience laugh through amusement and most often work by exaggerating characteristics for humorous effect.'
            ],
            [
                'name' => 'Horror',
                'enabled' => true,
                'description' => 'A film in which very frightening or unnatural things happen, for example dead people coming to life and people being murdered.'
            ],
            [
                'name' => 'Action',
                'enabled' => true,
                'description' => 'Action film is a film genre in which the protagonist or protagonists are thrust into a series of events that typically include violence, extended fighting, physical feats and frantic chases.'
            ],
            [
                'name' => 'Comedy',
                'enabled' => true,
                'description' => 'A comedy film is a category of film in which the main emphasis is on humor. These films are designed to make the audience laugh through amusement and most often work by exaggerating characteristics for humorous effect.'
            ],
            [
                'name' => 'Science fiction',
                'enabled' => true,
                'description' => 'Science fiction (sometimes shortened to sci-fi or SF) is a genre of speculative fiction that typically deals with imaginative and futuristic concepts such as advanced science and technology, space exploration, time travel, parallel universes, and extraterrestrial life.'
            ],
            [
                'name' => 'Thriller',
                'enabled' => true,
                'description' => 'Thriller film, also known as suspense film or suspense thriller, is a broad film genre that evokes excitement and suspense in the audience.'
            ],
            [
                'name' => 'Documentary',
                'enabled' => true,
                'description' => 'A documentary film or documentary is a non-fictional motion-picture intended to "document reality, primarily for the purposes of instruction, education, or maintaining a historical record"'
            ],
            [
                'name' => 'Crime',
                'enabled' => true,
                'description' => 'Crime films, in the broadest sense, are a cinematic genre inspired by and analogous to the crime fiction literary genre. Films of this genre generally involve various aspects of crime and its detection.'
            ], 
            [
                'name' => 'Historical',
                'enabled' => true,
                'description' => 'Movies that tell dramatic stories about events in the past are called historical dramas, and like all dramas they involve conflicts. They can be conflicts between characters and they can also be larger historical conflicts.'
            ], 
            [
                'name' => 'Sports',
                'enabled' => true,
                'description' => "The sports nonfiction book genre is made up of books containing information about specific sports and how they're played, as well as autobiographies, or biographies, of famous players or coaches of popular sports."
            ], 
            [
                'name' => 'Suspense',
                'enabled' => true,
                'description' => "A quality in a work of fiction that arouses excited expectation or uncertainty about what may happen."
            ], 
            [
                'name' => 'Children and Family',
                'enabled' => true,
                'description' => "A children's film, or family film, is a film genre that contains children or relates to them in the context of home and family. Children's films are made specifically for children and not necessarily for the general audience, while family films are made for a wider appeal with a general audience in mind."
            ], 
            [
                'name' => 'Violence',
                'enabled' => true,
                'description' => "In general, films depict bleeding, the immediate consequence of violence, more often than TV. In fact, horror movies celebrate gooey, graphic, gorey scenes. But even in these films, the real world consequences of violence — the physical handicaps, financial expense, and emotional cost — are never a part of the plot."
            ], 
            [
                'name' => 'Steamy',
                'enabled' => true,
                'description' => "These are the films filled with hot and heavy moments while mixing in at least a small attempt at a fascinating story and witty dialogue (they do say that the brain is the largest erogenous zone of them all.)"
            ], 
            [
                'name' => 'Gory',
                'enabled' => true,
                'description' => "The Gore and Disturbing film is a genre of horror film, easily recognized by its use of extreme violence and disturbing and viceral imagery, even for a horror film. In fact, in this genre recreating blood and gore with special effects are seen as an artform itself."
            ], 
        ]);
    }
}
