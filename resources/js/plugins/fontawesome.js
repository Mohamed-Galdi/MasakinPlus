import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

// Import icons you need from free packages
import { faUser, faHome, faCog, faSimCard, faCoins } from "@fortawesome/free-solid-svg-icons";
import { faComment } from "@fortawesome/free-regular-svg-icons";

// Add the icons to the library
library.add(faUser, faHome, faCog, faComment, faSimCard, faCoins);

export { FontAwesomeIcon };
