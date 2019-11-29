import Welcome from './components/Welcome';
import FoodStuffs from './components/Foodstuffs';
import FoodStuffShow from './components/FoodStuffShow';
import ProductSearchResult from './components/ProductSearchResult';
import SoupRecipes from './components/Recipes';
import ProductShow from './components/ProductShow';
import Proteins from './components/Proteins';
import FruitsVegs from './components/FruitsVegetables';
import HerbsSpices from './components/HerbsSpices';
import CoffeeTea from './components/CoffeeTea';
import Drinks from './components/Drinks';
import BreakfastCereal from './components/BreakfastCereal';
import EggsDairy from './components/EggsDairy';
import Toiletries from './components/Toiletries';
import Household from './components/Household';
import Groceries from './components/Groceries';
import Kitchen from './components/Kitchen';
import SpecialOrder from './components/SpecialOrder';
import Services from './components/Services';
import UserHome from './components/User/HomeUser';
import MyOrders from './components/User/MyOrders';
import MyOrder from './components/User/MyOrder';
import UserAccount from './components/User/Account';
import Messages from './components/User/Messages';
import AdminHome from './components/Admin/Home';
import AdminOrders from './components/Admin/Orders';
import AdminOrder from './components/Admin/Order';
import AdminMessage from './components/Admin/Message';
import AdminMessages from './components/Admin/Messages';
import AdminUsers from './components/Admin/Users';
import AdminUser from './components/Admin/User';
import AdminUserOrders from './components/Admin/UserOrders';
import AdminProducts from './components/Admin/Products';
import AdminProductShow from './components/Admin/ProductShow';
import AdminServiceShow from './components/Admin/ServiceShow';
import AdminCategories from './components/Admin/Categories';
import AdminLocations from './components/Admin/Locations';
import AdminTrxCharges from './components/Admin/TransactionCharges';
import AdminSetCharges from './components/Admin/SetCharges';
import AdminSpecialOrders from './components/Admin/SpecialOrders';
import AdminSpecialOrderShow from './components/Admin/SpecialOrderShow';
import AdminSettings from './components/Admin/Settings';
import AdminEnquiries from './components/Admin/Enquiries';
import AdminEnquiryMail from './components/Admin/EnquiryMail';
import paymentSuccessful from './components/PaymentSuccessful';
import AdminAllTodaysOrders from './components/Admin/AllTodaysOrders';

export default[
    {path: '/', name: 'Welcome', component: Welcome},
    {path: '/raw_foodstuffs', name: 'FoodStuffs', component: FoodStuffs},
    {path: '/raw_foodstuff/:id/:name', name: 'FoodStuffShow', component: FoodStuffShow},
    {path: '/search', name: 'ProductSearchResult', component: ProductSearchResult, props: true},
    {path: '/soup-stew-ingredients', name: 'SoupRecipes', component: SoupRecipes},
    {path: '/:cat/:id/:slug', name: 'ProductShow', component: ProductShow},
    {path: '/fish-meat', component:Proteins},
    {path: '/fruits-vegetables', component: FruitsVegs},
    {path: '/herbs-spices', component: HerbsSpices},
    {path: '/coffee-tea', component: CoffeeTea},
    {path: '/beverages-drinks-water', component: Drinks},
    {path: '/breakfast-cereal', component: BreakfastCereal},
    {path: '/eggs-dairy', component: EggsDairy},
    {path: '/toiletries', component: Toiletries},
    {path: '/household', component: Household},
    {path: '/groceries', component: Groceries},
    {path: '/kitchen', component: Kitchen},
    {path: '/special_order', component: SpecialOrder},
    {path: '/special_services/services', component: Services},
    {path: '/home', component: UserHome},
    {path: '/my_orders', component: MyOrders},
    {path: '/my_order/:orderId', name: 'MyOrder', component: MyOrder, props: true},
    {path: '/account', name: 'UserAccount', component: UserAccount, props: true},
    {path: '/messages', name: 'Messages', component: Messages},
    {path: '/admin', name: 'AdminHome', component: AdminHome},
    {path: '/admin/orders', name: 'AdminOrders', component: AdminOrders},
    {path: '/admin/orders/:id/:order', name: 'AdminOrder', component: AdminOrder},
    {path: '/admin/messages/:userId/:userSlug', name: 'AdminMessage', component: AdminMessage},
    {path: '/admin/messages', name: 'AdminMessages', component: AdminMessages},
    {path: '/admin/users', name: 'AdminUsers', component: AdminUsers},
    {path: '/admin/user/:user/:slug', name: 'AdminUser', component: AdminUser},
    {path: '/admin/user/:user/:slug/orders', name: 'AdminUserOrders', component: AdminUserOrders},
    {path: '/admin/products', name: 'AdminProducts', component: AdminProducts},
    {path: '/admin/product/:product/:slug', name: 'AdminProductShow', component: AdminProductShow},
    {path: '/admin/product/:product/service/:id/:slug', name: 'AdminServiceShow', component: AdminServiceShow},
    {path: '/admin/categories', name: 'AdminCategories', component: AdminCategories},
    {path: '/admin/locations', name: 'AdminLocations', component: AdminLocations},
    {path: '/admin/transaction_charges', name: 'AdminTrxCharges', component: AdminTrxCharges},
    {path: '/admin/set_charges', name: 'AdminSetCharges', component: AdminSetCharges},
    {path: '/admin/special_orders', name: 'AdminSpecialOrders', component: AdminSpecialOrders},
    {path: '/admin/special_order/:id/:special', name: 'AdminSpecialOrderShow', component: AdminSpecialOrderShow},
    {path: '/admin/settings', name: 'AdminSettings', component: AdminSettings},
    {path: '/admin/enquiries', name: 'AdminEnquiries', component: AdminEnquiries},
    {path: '/admin/enquiry/:mail/:slug', name: 'AdminEnquiryMail', component: AdminEnquiryMail},
    {path: '/payment_successful', name: 'paymentSuccessful', component: paymentSuccessful},
    {path: '/admin/all_orders_today', name: 'AdminAllTodaysOrders', component: AdminAllTodaysOrders},
]
