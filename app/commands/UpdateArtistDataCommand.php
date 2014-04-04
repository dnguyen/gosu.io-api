<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateArtistDataCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'data:update-artists';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates artists data.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$artists = DB::table('artists')->select('*')->get();

        foreach ($artists as $artist) {
            $artistName = $artist->name;
            $artistNameInKorean = '';

            // Regex match for: english_name(korean_name)
            $matchKoreanNameAtEnd = preg_match('/(*UTF8)(\(.*\p{Hangul}\)$)/', $artistName, $nameInKoreanMatches);
            // Regex match for: korean_name(english_name)
            $matchKoreanNameAtStart = preg_match('/(*UTF8)(^.*\p{Hangul}\()/', $artistName, $nameInKoreanMatches);

            if ($matchKoreanNameAtEnd) {
                $artistNameInKorean = $nameInKoreanMatches[0];

                // Update name to be english only
                $newArtistName = str_replace($artistNameInKorean, '', $artistName);

                // Remove parenthesis from korean name
                $artistNameInKorean = str_replace(['(', ')'], '', $artistNameInKorean);

                $this->line('Extracting Korean Name: ' . $artistNameInKorean);

            } else if ($matchKoreanNameAtStart) {
                $artistNameInKorean = $nameInKoreanMatches[0];
                $artistNameInKorean = str_replace(['(', ')'], '', $artistNameInKorean);
                $newArtistName = str_replace([$artistNameInKorean, '(', ')'], '', $artistName);
                $this->line('korean: ' . $artistNameInKorean);
                $this->line('english: ' . $newArtistName);
            }

            if ($matchKoreanNameAtStart || $matchKoreanNameAtEnd) {
                DB::table('artists')
                    ->where('id', '=', $artist->id)
                    ->update(
                        array(
                            'name' => $newArtistName,
                            'korean_name' => $artistNameInKorean
                        )
                    );
            }
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}