import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

// Import icons you need from free packages
import {
    faUser,
    faHome,
    faCoins,
    faArrowRight,
    faLocationDot,
    faStar,
    faBell,
    faBars,
    faXmark,
    faWallet,
    faHeadset,
    faChartPie,
    faBuilding,
    faFileContract,
    faCircleDollarToSlot,

} from "@fortawesome/free-solid-svg-icons";

import { faBookmark,  } from "@fortawesome/free-regular-svg-icons";



// Add the icons to the library
library.add(
    faUser,
    faHome,
    faCoins,
    faArrowRight,
    faLocationDot,
    faStar,
    faBookmark,
    faBell,
    faBars,
    faXmark,
    faWallet,
    faHeadset,
    faChartPie,
    faBuilding,
    faFileContract,
    faCircleDollarToSlot,
);

export { FontAwesomeIcon };
