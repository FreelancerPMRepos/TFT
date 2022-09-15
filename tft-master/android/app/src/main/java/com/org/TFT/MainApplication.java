package com.org.TFT;

// Add CodePush imports
import com.microsoft.codepush.react.CodePush;
import android.app.Application;
import android.content.Context;
import com.facebook.react.PackageList;
import com.facebook.react.ReactApplication;
import com.cmcewen.blurview.BlurViewPackage;
import org.wonday.orientation.OrientationPackage;
import com.th3rdwave.safeareacontext.SafeAreaContextPackage;
import com.BV.LinearGradient.LinearGradientPackage;
import com.airbnb.android.react.lottie.LottiePackage;
import io.invertase.firebase.perf.ReactNativeFirebasePerfPackage;
import io.invertase.firebase.analytics.ReactNativeFirebaseAnalyticsPackage;
import io.invertase.firebase.messaging.ReactNativeFirebaseMessagingPackage; 
import io.invertase.firebase.app.ReactNativeFirebaseAppPackage;
import io.invertase.firebase.auth.ReactNativeFirebaseAuthPackage;
import com.reactnativecommunity.webview.RNCWebViewPackage;
import com.facebook.react.ReactNativeHost;
import com.facebook.react.ReactPackage;
import com.facebook.soloader.SoLoader;
import java.lang.reflect.InvocationTargetException;
import java.util.List;

public class MainApplication extends Application implements ReactApplication {

  private final String CODEPUSH_STAGING = "74NJ063_oIZVTuRCOgP702FoWM5-ZV5ZLYckl";
  private final String CODEPUSH_PRODUCTION = "W3N4AOGjyHcWglz0KOGF0bkdelzK3WGcQ0ANb";

  private final ReactNativeHost mReactNativeHost = new ReactNativeHost(this) {
    @Override
    public boolean getUseDeveloperSupport() {
      return BuildConfig.DEBUG;
    }

    @Override
    protected List<ReactPackage> getPackages() {
      @SuppressWarnings("UnnecessaryLocalVariable")
      List<ReactPackage> packages = new PackageList(this).getPackages();
      // Packages that cannot be autolinked yet can be added manually here, for
      // example:
      // packages.add(new MyReactNativePackage());
      // packages.add(new ReactNativeFirebaseAuthPackage());
      // packages.add(new CodePush(CODEPUSH_STAGING, getApplicationContext(), BuildConfig.DEBUG));
      return packages;
    }

    @Override
    protected String getJSBundleFile() {
        return CodePush.getJSBundleFile();
    }

    @Override
    protected String getJSMainModuleName() {
      return "index";
    }
  };

  @Override
  public ReactNativeHost getReactNativeHost() {
    return mReactNativeHost;
  }

  @Override
  public void onCreate() {
    super.onCreate();
    SoLoader.init(this, /* native exopackage */ false);
    initializeFlipper(this); // Remove this line if you don't want Flipper enabled
  }

  /**
   * Loads Flipper in React Native templates.
   *
   * @param context
   */
  private static void initializeFlipper(Context context) {
    if (BuildConfig.DEBUG) {
      try {
        /*
         * We use reflection here to pick up the class that initializes Flipper, since
         * Flipper library is not available in release mode
         */
        Class<?> aClass = Class.forName("com.facebook.flipper.ReactNativeFlipper");
        aClass.getMethod("initializeFlipper", Context.class).invoke(null, context);
      } catch (ClassNotFoundException e) {
        e.printStackTrace();
      } catch (NoSuchMethodException e) {
        e.printStackTrace();
      } catch (IllegalAccessException e) {
        e.printStackTrace();
      } catch (InvocationTargetException e) {
        e.printStackTrace();
      }
    }
  }
}
