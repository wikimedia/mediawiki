
/*
 * WikiSearchThread is a background thread that does searches.
 * See WikiFetchThread for more details on design.
 */

package com.piclab.wikitest;
import com.meterware.httpunit.*;

public class WikiSearchThread extends Thread {

private WebConversation m_conv;
private int m_totalsearches;
private long m_totaltime;
private volatile boolean m_running;

public WikiSearchThread() {
	m_conv = new WebConversation();
	m_totalsearches = 0;
	m_totaltime = 0;
}

public int getSearches() { return m_totalsearches; }
public long getTime() { return m_totaltime; }
public void requestStop() { m_running = false; }

/*
 * First, a list of miscellaneous words that do appear in the
 * titles and text of the testing articles.
 */

public static String[] searchterms = {
	"agriculture", "husbandry", "corn", "vegetable", "farming", "nitrogen",
	"anthropology", "human", "culture", "language", "networks", "society",
	"archaeology", "archeology", "history", "marxism", "ownership", "land",
	"architecture", "building", "landscape", "furniture", "carpentry", "roman",
	"astronomy", "astrophysics", "astrology", "star", "cosmology", "galaxy",
	"biology", "life", "monet", "evolution", "species", "animal", "plant",
	"business", "industry", "capitalism", "commerce", "company", "corporation",
	"chemistry", "atom", "organic", "element", "alchemy", "polymer",
	"classics", "greece", "rome", "latin", "literature", "mythology", "art",
	"communication", "media", "television", "radio", "film", "mail",
	"computer", "engineering", "linguistics", "algorithm", "graphics", "logic",
	"cooking", "food", "heat", "cuisine", "ethnic", "nutrition", "flavor",
	"critical", "theory", "frankfurt", "postmodernism", "weber",
	"dance", "rhythm", "music", "recreation", "performance", "ballet",
	"earth", "science", "geology", "weather", "fossil", "ocean", "environment",
	"economic", "scarcity", "communism", "socialism", "utility", "money",
	"education", "teaching", "knowledge", "reading", "testing", "school",
	"technology", "civil", "mechanical", "nuclear", "process", "control",
	"entertainment", "animation", "sport", "humor", "illusion", "theater",
	"family", "consumer", "parenting", "sewing", "homemaker", "decoration",
	"movie", "cinema", "director", "actor", "genre", "studio", "festival",
	"game", "card", "board", "competition", "probability", "drinking", "dice",
	"geography", "map", "projection", "continent", "island", "river", "sea",
	"handicraft", "bead", "marquetry", "paper", "wood", "garden", "metal",
	"history", "etymology", "orthodox", "controversy", "pasteur", "method",
	"hobby", "pastime", "professional", "amateur", "collecting", "genealogy",
	"language", "othography", "writing", "alphabet", "phonetic", "speech",
	"law", "taboo", "more", "jurisdiction", "legislature", "judge", "police",
	"library", "information", "book", "journal", "pediodical", "database",
	"philology", "syntax", "semantic", "lexicology", "comparative", "cipher",
	"letter", "rhetoric", "bible", "poem", "novel", "epic", "essay", "drama",
	"math", "mathematics","statistics", "number", "algebra", "calculus",
	"music", "melody", "instrument", "ensemble", "orchestra", "harmony",
	"opera", "costume", "dialogue", "acting", "voice", "libretto", "stage",
	"painting", "glaze", "acrylic", "mural", "portrait", "canvas", "fresco",
	"philosophy", "concept", "dialectic", "beauty", "ethic", "aristotle",
	"physics", "matter", "space", "energy", "quantum", "particle", "momentum",
	"poker", "stud", "wager", "gambling", "joker", "flush", "chip", "deal",
	"political", "politics", "government", "violence", "democracy", "fascism",
	"psychology", "freud", "ethology", "medicine", "therapy", "drug", "health",
	"public", "policy", "activism", "defense", "tax", "administration",
	"recreation", "weekend", "holiday", "vacation", "leisure", "sex",
	"religion", "christianity", "judaism", "islam", "deity", "faith", "priest",
	"sculpture", "clay", "marble", "mobile", "kinetic", "statue", "bust",
	"sociology", "kinship", "criminology", "race", "revolution", "gender",
	"sport", "equipment", "injury", "spectator", "football", "baseball",
	"invention", "recording", "cryptography", "metallurgy", "hydraulic",
	"theatre", "mime", "tennessee", "lighting", "scenery", "improvisation",
	"tourism", "travel", "sightseeing", "hotel", "camping", "cruise",
	"transport", "vehicle", "airline", "train", "ferry", "subway", "car",
	"visual", "design", "photography", "fashion", "tattoo", "textile"
};

/*
 * Then, a list of miscellaneous words that may or may not appear
 * in the test articles.
 */

public static String[] randomterms = {
	"abatement", "acacia", "aerate", "allergy", "anvil", "ashtray", "auger",
	"badger", "bakery", "benign", "biceps", "bookie", "brazen", "bulldog",
	"caliber", "castigate", "centipede", "chemise", "clamor", "cupboard",
	"dawn", "debris", "derrick", "dig", "divorce", "doublet", "drummer",
	"ebony", "eclipse", "elitist", "emulator", "escrow", "euphoria", "evade",
	"famine", "feedback", "fiefdom", "flax", "fox", "freckle", "funnel",
	"gallop", "ghetto", "gingham", "gnat", "gossip", "grudge", "guitar",
	"halogen", "hedgehog", "heuristic", "hillbilly", "hologram", "hyacinth",
	"idealism", "illustrator", "impeach", "income", "injunction", "irony",
	"janitor", "jellyfish", "jitterbug", "journalism", "juggling", "jury",
	"kamikaze", "kerosene", "kindergarten", "kitten", "klaxon", "knuckle", 
	"lager", "leech", "lentil", "libido", "locust", "lox", "lullabye", "lyre",
	"magenta", "marigold", "mediator", "mileage", "monarch", "municipal",
	"navigation", "neurosis", "nicotine", "nostalgia", "nucleus", "nymph",
	"oasis", "obscene", "oilcloth", "oratory", "osmosis", "ovary", "owl",
	"parsley", "perplex", "phony", "pilgrim", "pliers", "pompadour", "prose",
	"quahog", "quaver", "quench", "queue", "quilt", "quince", "quotient",
	"railroad", "ravine", "recipe", "rescue", "rig", "roast", "ruthless",
	"sarcasm", "sclerosis", "sellout", "shanty", "sigma", "skyhook", "synod",
	"tantrum", "tenacious", "thorn", "tithe", "tonsil", "trauma", "tyranny",
	"ulcer", "umpire", "unicorn", "unravel", "urinate", "upload", "utensil",
	"valence", "veranda", "viewpoint", "volunteer", "vow", "vulnerable",
	"wanton", "welcome", "wharf", "whiz", "wilderness", "woofer", "wretch",
	"xanthate", "yeast", "yeoman", "yonder", "zealous", "zen", "zonal"
};

public void run() {
	int tindex = 0, rindex = 0;
	double r;
	long start, end;
	WebResponse wr;

	String prefix = WikiSuite.getServer() + WikiSuite.getScript() + "?search=";
	String term = null;

	m_running = true;
	while ( m_running ) {
		r = Math.random();
		if ( r < 0.1 ) {
			term = searchterms[tindex] + " AND " + randomterms[rindex];
		} else if ( r < 0.3 ) {
			term = searchterms[tindex];
			if ( ++tindex >= searchterms.length ) { tindex = 0; }
			term += " AND " + searchterms[tindex];
		} else if ( r < 0.4 ) {
			term = searchterms[tindex] + " OR " + randomterms[rindex];
		} else if ( r < 0.5 ) {
			term = randomterms[rindex];
			if ( ++rindex >= randomterms.length ) { rindex = 0; }
			term += " OR " + randomterms[rindex];
		} else if ( r < 0.7 ) {
			term = randomterms[rindex];
		} else {
			term = searchterms[tindex];
		}

		start = System.currentTimeMillis();
		try {
			term = java.net.URLEncoder.encode( term, "UTF-8" );
			wr = m_conv.getResponse( prefix + term );
		} catch ( java.io.UnsupportedEncodingException e ) {
			break;
		} catch (Exception e) {
			WikiSuite.warning( "Error (" + e + ") searching for \"" + term + "\"" );
		}
		end = System.currentTimeMillis();

		WikiSuite.finer( "Searched for \"" + term + "\"" );
		++m_totalsearches;
		m_totaltime += ( end - start );

		if ( ++tindex >= searchterms.length ) { tindex = 0; }
		if ( ++rindex >= randomterms.length ) { rindex = 0; }

		try {
			Thread.sleep( 3000 );
		} catch( InterruptedException e ) {
			break;
		}
	}
	synchronized (this) { notify(); }
}

}
